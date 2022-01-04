<?php

namespace Application\Service\Product;

use Application\Constant\ProductConstants;
use Application\Constant\RedisConstants;
use Application\Constant\SystemConstants;
use Application\Domain\Product\Product;

use Application\Domain\Product\ProductRelatedResource;
use Application\Domain\System\Resource;
use Application\Exception\ValidatorParamsException;
use Application\Exception\ProductDateNotFind;
use Application\Helper\ClassHelper;
use Application\Helper\RedisHelper;
use Application\Helper\UploadHelper;
use Application\Model\CategoryModelInterfaceInterface;
use Application\Model\ProductInfoModelInterFaceInterface;
use Application\Model\ProductModelInterfaceInterFace;
use Application\Model\ProductRelatedResourceModelInterface;

use Application\Model\ResourceModelInterfaceInterface;
use Exception;

class ProductService implements \Application\Service\ProductServiceInterface
{
    /**
     * @var ProductModelInterfaceInterFace
     */
    private $productModel;
    /**
     * @var ProductInfoModelInterFaceInterface
     */
    private $productInfoModel;
    /**
     * @var ProductRelatedResourceModelInterface
     */
    private $productRelatedResourceModel;
    /**
     * @var CategoryModelInterfaceInterface
     */
    private $categoryModel;
    /**
     * @var RedisHelper
     */
    private $redisHelper;
    /**
     * @var ResourceModelInterfaceInterface
     */
    private $resourceModel;

    public function __construct(ResourceModelInterfaceInterface $resourceModel, ProductModelInterfaceInterFace $productModel, ProductInfoModelInterFaceInterface $productInfoModel, ProductRelatedResourceModelInterface $productRelatedResourceModel, CategoryModelInterfaceInterface $categoryModel, RedisHelper $clint)
    {
        $this->productModel = $productModel;
        $this->productInfoModel = $productInfoModel;
        $this->productRelatedResourceModel = $productRelatedResourceModel;
        $this->categoryModel = $categoryModel;
        $this->redisHelper = $clint;
        $this->resourceModel = $resourceModel;
    }

    /**
     * @throws Exception
     */
    public function updateProductDetail(Product $product, array $resources, array $productInfo): bool
    {
        // 开启事务
        $this->productModel->startTransactional();
        try {
            // 更新商品信息
            $this->productModel->updateProduct($product);
            // 更新商品的规格信息
            foreach ($productInfo as $info) {
                $this->productInfoModel->updateProductInfo($info);
            }
            // 事务提交
            $this->productModel->commit();

        } catch (Exception $e) {
            // 事务回滚
            $this->productModel->rollback();
            throw  $e;
        }
        return true;
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.17 11:00
     * Describe  保存商品详情
     * @param Product $product
     * @param array $resources
     * @param array $productInfo
     * @return bool[]
     * @throws Exception
     */
    public function saveProductDetail(Product $product, array $resources, array $productInfo): bool
    {
        // 开启事务
        $this->productModel->startTransactional();
        try {
            // 获取商品信息
            $productId = $this->productModel->saveProduct($product);

            // 填充商品Id
            foreach ($productInfo as &$info) {
                $info->productId = $productId;
            }
            foreach ($resources as &$resource) {
                $resource->productId = $productId;
            }
            // 保存商品详情
            $this->productInfoModel->saveProductInfoBatch($productInfo);
            // 保存商品资源信息
            $this->productRelatedResourceModel->saveProductRelatedResourceBatch($resources);
            // 事务提交
            $this->productModel->commit();

        } catch (Exception $e) {
            // 事务回滚
            $this->productModel->rollback();
            throw  $e;
        }
        return true;
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.17 13:11
     * Describe 获取商品详情
     * @param int $productId
     * @return array
     */
    public function getProductDetail(int $productId): array
    {
        /** @var Product */
        // 为获取商品
        $product = $this->productModel->getProductById($productId);

        if (empty($product->id)) {
            throw new ProductDateNotFind("无法找到商品");
        }
        // 获取商品的分类名
        $category = $this->categoryModel->getCategoryById($product->categoryId);
        // 获取商品参数信息
        $productInfo = $this->productInfoModel->listProductInfoByProductId($product->id);
        // 获取商品图片 访问路径
        $resources =  $this->getImageAccessPath($product);
        // 属性转换
        $product = ClassHelper::newArrayByObject($product);
        $category = ClassHelper::newArrayByObject($category);
        $productInfo = ClassHelper::newArrayByObjectArr($productInfo);
        // 数据合并
        $product['categoryName'] = $category['categoryName'];
        $product['url'] = $resources;
        $product['productInfo'] = $productInfo;
        $this->recordProductClick($productId);
        return $product;
    }

    public function removeProductById(int $id): bool
    {
        // 开启事务
        $this->productModel->startTransactional();
        try {
            // 删除商品信息
            $delProduct = ($this->productModel->removeProductByIds([$id]));
            // 删除商品详情
            $delProductInfo = ($this->productInfoModel->removeProductInfoByProductId($id));
            // 删除商品关联资源
            $delProductRelated = $this->productRelatedResourceModel->removeProductRelatedResourceByProductId($id);
            // 事务提交
            if ($delProduct && $delProductInfo && $delProductRelated) {
                $this->productModel->commit();
            } else {
                $this->productModel->rollback();
                return false;
            }
        } catch (Exception $e) {
            // 事务回滚
            $this->productModel->rollback();
            throw  $e;
        }
        return true;
        return true;
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.21 16:13
     * Describe 记录商品被点击 记录
     * @param int $productId
     */
    private function recordProductClick(int $productId): void
    {
        // 检查redis是否存在key
        if ($this->redisHelper->existKey(RedisConstants::$PRODUCT_HOT_KEY)) {
            // 获取
            $this->redisHelper->zIncrBy(RedisConstants::$PRODUCT_HOT_KEY, $productId);
            // 设置过期时间
            $this->redisHelper->expireAt(RedisConstants::$PRODUCT_HOT_KEY);
        } else {
            // 记录权重
            $this->redisHelper->zIncrBy(RedisConstants::$PRODUCT_HOT_KEY, $productId);
        }
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.21 16:52
     * Describe 获取点击最多的商品
     * @param int $start
     * @param int $end
     * @return array
     */
    private function getMHotProductIdsByUserClick(int $start, int $end = -1): array
    {
        if ($end < -1 || $start < 0) {
            throw  new ValidatorParamsException("排序区间设置错误 start = $start , end = $end");
        }
        return $this->redisHelper->zRevRange(RedisConstants::$PRODUCT_HOT_KEY, $start, $end);
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.21 17:21
     * Describe
     * 获取最热商品的ID
     * 获取商品信息
     * 获取商品的相关信息
     * 数据的格式化
     * @param int $start
     * @param int $end
     * @return array
     */
    public function listProductByHot(int $start, int $end): array
    {


        // 获取最热商品的ID
        $hotProductIds = $this->getMHotProductIdsByUserClick($start, $end);
        // 获取商品信息
        $products = $this->productModel->listProductByIds($hotProductIds);
        // 获取商品的相关信息
        foreach ($products as &$product) {
            // 获取商品标签
            // 获取商品展示图
        }

        // 数据的格式化
        return [];
    }

    public function getImageAccessPath(Product $product):array
    {
        $images = [];
        $resources =  $this->productRelatedResourceModel->findProductResourceByProductIdAndType($product->id, ProductConstants::$PRODUCT_IMAGE_TYPE_DETAIL);
        /**
         * @var $resource Resource
         */
        foreach ($resources as $resource){
            $images[] = UploadHelper::getFileAccessPath($resource->url);
        }
        return  $images;
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.22 15:06
     * Describe 获取商品的轮播信息
     * @return array  [ [productId : id , imgUrl :imgAccessPatch]]
     */
    public function listProductCarousel(): array
    {

        // 返回的数据
        $res = [];
//        // 从商品资源关联表中获取轮播商品信息 只要返回四条数据就可以了
//        $relations = $this->getBannerProductIds();
//        /*** @var $relation ProductRelatedResource */
//        foreach ($relations as $relation) {
//            // 获取商品的图片信息
//            $temp = [];
//            $temp['productId'] = $relation->productId;
//            $accessPath = $this->getProductImgAccessPath($relation->id);
//            $temp['imgUrl'] = $accessPath;
//            $res[] = $temp;
//        }
        return $res;
    }

    /**




    /**
     * User: 无畏泰坦
     * Date: 2021.12.22 17:35
     * Describe 查询商品
     * @param Product $product
     * @param int $page
     * @param int $size
     * @return array
     */
    public function listProduct(Product $product, int $page, int $size): array
    {
        $res = [];
        // 查询总数
        $total = $this->productModel->countProduct($product);
        // 查询数据
        $products  = $this->productModel->listProduct($product,$page,$size);

        $products =  ClassHelper::newArrayByObjectArr($products);
        foreach ($products as $item){
            $item['imgUrl'] =$this->getProductShowImgAccessPath($item['productId']);
        }
        // 获取商品分类
        return ['total' => $total,'data' => $products];
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.22 17:38
     * Describe  获取商品的展示图
     * @return array
     */
    private function getProductShowImgAccessPath(int $productId): string
    {
       $resource =  $this->productRelatedResourceModel->findProductResourceByProductIdAndType($productId,ProductConstants::$PRODUCT_IMAGE_TYPE_SHOW);
       if (empty($resource)){
           return  "#";
       }else{
           return $resource[0]->url;
       }
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.22 15:06
     * Describe 获取首页轮播图 放回轮播图片的地址和商品Id
     * @return array
     */
    public function getBannerProduct(): array
    {
        // 商品轮播信息集合
        $bannerArr = [];
        // 获取轮播商品信息 ： 商品Id(productId) 和资源id(resourceId)
        $productInfo = $this->getBannerProductInfo();
        foreach ($productInfo as $info) {
            $banner = [];
            $banner['productId'] = $info['productId'];
            // 获取商品的图片信息
            $accessPath = $this->getProductImgAccessPath( $info['resourceId']);
            $banner['imgUrl'] = $accessPath;
            $bannerArr[] = $banner;
        }
        return $bannerArr;
    }
    /**
     * User: 无畏泰坦
     * Date: 2021.12.22 15:28
     * Describe 获取轮播商品信息的Ids
     * @return array
     *
     */
    private function getBannerProductInfo(): array
    {
        $productInfo =  $this->productRelatedResourceModel->listProductRelatedResource(resourceType: ProductConstants::$PRODUCT_BANNE_IMAGE_TYPE,
                                                                                        select:['product_id','resource_id'], page: 1,size: 4);
        $resourceIds = array_column($productInfo,'resourceId');
        array_multisort($resourceIds,SORT_DESC,$productInfo);
        return  $productInfo;
    }
    /**
     * User: 无畏泰坦
     * Date: 2021.12.22 15:29
     * Describe 获取商品的访问路径
     * @param int $resourceId
     * @return string
     */
    private function getProductImgAccessPath(int $resourceId): string
    {
        /**
         * @var Resource
         */
        $resource = $this->resourceModel->getResourceById($resourceId);
        return empty($resource->url) ? "#" : SystemConstants::$FILE_ACCESS_PATH_PREFIX.$resource->url;
    }

    public function getHotProduct(int $productType = 0): array
    {
        return [];
    }

}
