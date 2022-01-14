<?php

namespace Application\Service\Product;


use Application\Constant\ProductConstants;
use Application\Domain\Product\Category;
use Application\Domain\Product\ProductRelatedResource;
use Application\Domain\VO\TreeVO;
use Application\Helper\ClassHelper;
use Application\Helper\UploadHelper;
use Application\Model\CategoryModelInterface;
use Application\Model\ProductRelatedResourceModelInterface;
use Application\Model\ResourceModelInterface;
use Medoo\Medoo;
use mon\util\Tree;
use Psr\Container\ContainerInterface;

class CategoryService implements \Application\Service\CategoryServiceInterface
{
    private $categoryModel;
    private $relatedResourceModel;
    private $resourceModel;

    public function __construct(CategoryModelInterface $categoryModel, ProductRelatedResourceModelInterface $relatedResourceModel, ResourceModelInterface $resourceModel)
    {
        $this->categoryModel = $categoryModel;
        $this->relatedResourceModel = $relatedResourceModel;
        $this->resourceModel = $resourceModel;
    }

    public function saveCategory(Category $category, array $resource): int
    {
        //保存商品分类信息
        $categoryId = $this->categoryModel->saveCategory($category);
        //保存商品分类图标信息
        /**
         * @var  $r ProductRelatedResource
         */
        foreach ($resource as &$r) {
            $r->productId = $categoryId;
        }
        $this->relatedResourceModel->saveProductRelatedResourceBatch($resource);
        return true;
    }

    public function updateCategory(Category $category, array $resources): bool
    {

        $this->categoryModel->updateProductById($category);
        /**
         * @var $resource ProductRelatedResource
         */
        foreach ($resources as &$resource) {
            $resource->productId = $category->id;
            if (!$this->relatedResourceModel->removeProductRelatedResourceByProductIdAndReSourceType($resource->productId,$resource->resourceType) ){

                return false;
            }
            $resource->id = null;
        }
        $this->relatedResourceModel->saveProductRelatedResourceBatch($resources);

        return true;
    }


    /**
     * User: 无畏泰坦
     * Date: 2021.12.16 10:54
     * Describe 分页查询数据
     * @param Category $category
     * @param int $page
     * @param int $size
     * @return array|mixed
     */
    public function listCategory($queryCondition): array
    {
        // 查询总数
        $total = $this->categoryModel->countCategory($queryCondition);
        // 查询数据
        $data = $this->categoryModel->listCategory(queryCondition: $queryCondition);
        // 转换变量名称
        $data = ClassHelper::newArrayByObjectArr($data);
        return ['total' => $total, 'data' => $data];
    }

    public function deleteCategoryById(int $id): bool
    {

        // 获取商品分类
        $res = $this->categoryModel->getCategoryAll(new Category());
        $tree = new TreeVO();
        // 初始化root节点
        $tree->id = 0;
        $tree->label = "根目录";
        // 记录每一个节点的子节点
        $map = array();
        foreach ($res as $d) {
            if (array_key_exists($d->parentId, $map)) {
                $temp =  &$map[$d->parentId];
                $temp[] = $d;
                $map[$d->parentId] = $temp;
            } else {
                $map[$d->parentId] = array($d);
            }
        }
        // 开始创建树 以删除的节点为根节点
        $this->buildTree($map, $id, $tree);
        // 获取需要删除的节点
        $childId = $this->getTreeId($tree);
        $ids = array_merge([$id], $childId);
        return $this->categoryModel->removeCategoryByIds($ids);
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.31 13:01
     * Describe  查询商品分类并把结果构建成树形结构
     * @param array $queryCondition
     * @param array $select
     * @return TreeVO
     */
    public function listCategoryByTree(array $queryCondition = [], array $select = []): TreeVO
    {
        // 获取商品分类
        $categories = $this->categoryModel->listCategory($queryCondition,$select);
        // 返回的树结构
        $tree = new TreeVO();
        // 初始化root节点
        $tree->id = 0;
        $tree->label = "根目录";
        // 记录每一个节点的子节点
        $map = array();
        /*** @var $item Category */
        foreach ($categories as $item) {
            if (array_key_exists($item->parentId, $map)) {
                $temp =  &$map[$item->parentId];
                $temp[] = $item;
                $map[$item->parentId] = $temp;
            } else {
                $map[$item->parentId] = array($item);
            }
        }
        // 开始创建树 默认从parentId为-1
        $this->buildTree($map, ProductConstants::$PRODUCT_CATEGORY_ROOT_ID, $tree);
        $tree->total = count($tree->children);
        // 分页返回查询结果,按照第一层树节点分页

        if (!array_key_exists('page',$queryCondition) ||$queryCondition['page'] === -1 || $queryCondition['size']===-1) {
            return $tree;
        } else {
            $end = min($queryCondition['page'] * $queryCondition['size'], count($tree->children));
            $tree->children = array_slice($tree->children, ($queryCondition['page'] - 1) * $queryCondition['size'], $end);
            return $tree;
        }
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.16 10:56
     * Describe 构建树结构1
     * @param $map
     * @param $parentId
     * @param TreeVO $parentNode
     */
    private function buildTree(&$map, $parentId, TreeVO &$parentNode)
    {

        if (!array_key_exists($parentId, $map)) {
            return;
        }
        $children =  &$map[$parentId];
        $parentNode->children = [];
        foreach ($children as $child) {
            $node = new TreeVO();
            $node->label = $child->name;
            $node->id = $child->id;

            $data = ClassHelper::newArrayByObject($child);
            $icon = [];
            $data['resources'] = [];
            $resources = $this->resourceModel->findProductResourceByProductIdAndType($child->id, ProductConstants::$PRODUCT_CATEGORY_IMAGE_TYPE_ICON);
            $icon['resourceId'] = $resources[0]->id;
            $icon['resourceType'] = ProductConstants::$PRODUCT_CATEGORY_IMAGE_TYPE_ICON;
            $icon['fileAccessPath'] = UploadHelper::getFileAccessPath($resources[0]->url);
            $data['resources'][] = $icon;
            $bigIcon = [];
            $resources = $this->resourceModel->findProductResourceByProductIdAndType($child->id, ProductConstants::$PRODUCT_CATEGORY_IMAGE_TYPE_BIGICON);
           foreach ($resources as  $resource){
               $bigIcon['resourceId'] = $resource->id;
               $bigIcon['fileAccessPath'] = UploadHelper::getFileAccessPath($resource->url);
               $bigIcon['resourceType'] = ProductConstants::$PRODUCT_CATEGORY_IMAGE_TYPE_BIGICON;
               $data['resources'][] = $bigIcon;
           }

            $node->detail = $data;
            $parentNode->children[] = $node;
            $this->buildTree($map, $child->id, $node);
        }
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.16 13:57
     * Describe 获取树的节点Id
     * @param TreeVO $root
     */
    private function getTreeId(TreeVO $root): array
    {
        if (empty($root->children)) {
            return [];
        }
        $res = [];
        foreach ($root->children as $child) {
            $res[] = $child->id;
            $this->getTreeId($child);
        }
        return $res;
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.23 15:35
     * Describe 获取首页展示的商品分类
     * @return bool
     */
    public function getIndexCategory(): TreeVO
    {
        $res = [];
        $queryCondition = ['type'=>ProductConstants::$PRODUCT_CATEGORY_INDEX_TYPE];
        $select  = ['name','parent_id','id'];
        return $this->listCategoryByTree($queryCondition,$select);
    }


}
