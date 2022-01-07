<?php

namespace Application\Model;

use Application\Domain\Product\Category;

/**
 * User: 无畏泰坦
 * Date: 2021.12.16 10:48
 * Describe 商品分类在持久化操作
 */
interface CategoryModelInterfaceInterface extends BaseModelInterface
{
    /**
     * User: 无畏泰坦
     * Date: 2021.12.16 10:46
     * Describe 统计查询的数量
     * @param array $queryCondition
     * @return int
     */
    public function countCategory(array $queryCondition):int;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.16 10:47
     * Describe 分页查询商品分类
     * @param array $queryCondition
     * @param array $select
     * @param array $limit
     * @return array
     */
    public function listCategory(array $queryCondition = [],array $select = [],array $limit=[]): array;

    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 11:03
     * Describe
     * @param array $queryCondition
     * @param array $select
     * @return array
     */
    public function getCategory(array $queryCondition = [],array $select = []):array;
    /**
     * User: 无畏泰坦
     * Date: 2021.12.16 13:25
     * Describe 查询所有的商品分类
     * @param Category $category
     * @return array
     */
    public function getCategoryAll(Category $category):array;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.17 11:21
     * Describe 删除商品分类
     * @param array $ids
     * @return bool
     */
    public function removeCategoryByIds(array $ids):bool;

    public function updateProductById(Category $category):bool;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.21 11:44
     * Describe 分类保存
     *
     * @param Category $category
     * @return int
     */
    public function saveCategory(Category $category):int;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.17 11:22
     * Describe 通过商品分类Id获取一条记录
     * @param $id
     * @return Category
     */
    public function getCategoryById(int $id):Category;

    public function getCategoryAllByParentId(int $parentId):array;

}
