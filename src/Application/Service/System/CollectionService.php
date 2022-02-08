<?php
/**
 * Collect: 无畏泰坦
 * Date: 2021.12.15 16:55
 * Describe
 */

namespace Application\Service\System;

use Application\Constant\ErrorEnum;
use Application\Domain\System\Collect;
use Application\Exception\CommonException;
use Application\Exception\ModelException;
use Application\Exception\ServiceException;
use Application\Model\CollectMenuModelInterface;
use Application\Model\CollectionModelInterface;
use Application\Model\Impl\ProductModel;
use Application\Service\BaseService;
use Application\Service\CollectionServiceInterface;
use Application\Service\Product\ProductService;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Created on 2021.12.15 16:55
 * Created by 无畏泰坦
 * Describe
 */
class CollectionService extends  BaseService implements CollectionServiceInterface
{
    /**
     * @var CollectionModelInterface
     */
    private $collectModel;


    private $productService;
    /**
     * @var CollectionModelInterface
     */
    private $productModel;
    public function __construct(CollectionModelInterface $CollectModel, ProductModel $productModel,ProductService $productService)
    {
        $this->collectModel = $CollectModel;
        $this->productModel = $productModel;
        $this->productService = $productService;
    }



    /**
     * Collect: 无畏泰坦
     * Date: 2022.01.05 17:43
     * Describe  分页查询用户收藏
     * @param array $query
     * @return array
     */
    #[ArrayShape(['total' => "int", 'data' => "array"])] public function listCollectByPage(array $query = []): array
    {
        $collectProduct = [];
        $total = $this->collectModel->countCollect($query);
        $pageParams = $this->getPageParams($query,$total);
         $collects = $this->collectModel->findCollect(queryCondition: $query,limit: $pageParams);


        if (!empty($collects)){
            foreach ($collects as &$collect){
                $product = $this->productModel->getProductById($collect['product_id']);
                $product->file_path = $this->productService->getProductShowImgAccessPath($product->id);
                $product->collectId= $collect['id'];
                $collectProduct[] = $product;
            }
        }
        return ['total' => $total,'data' => $collectProduct];
    }


    /**
     * Collect: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe
     * @param int $collectId
     * @param int $userId
     * @return bool
     */
    public function removeCollect(int  $collectId,int $userId): bool
    {
        $collect = $this->collectModel->getCollect(query: ['user_id'=>$userId,'id'=>$collectId]);
        if (empty($collect)){
            throw  new CommonException(errorInfo: ErrorEnum::$ERROR_20003);
        }
       return $this->collectModel->removeCollect([$collectId]);
    }
    /**
     * Collect: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe 数据保存
     * @param array $collect
     * @return int
     */
    public function saveCollect(array $collect): int
    {
        $queryCollection = $this->collectModel->getCollect(query: ['product_id'=>$collect['product_id'],'user_id'=>$collect['user_id']]);
        if (empty($queryCollection)){
            return $this->collectModel->saveCollect($collect);
        }
        return  true;
    }



}
