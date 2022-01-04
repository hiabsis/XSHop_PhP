<?php

namespace Application\Service;

use Application\Domain\Product\Category;
use Application\Domain\VO\TreeVO;

interface CategoryServiceInterface
{
    /**
     * User: 无畏泰坦
     * Date: 2021.12.31 11:00
     * Describe  保存分类
     * @param Category $category
     * @param array $resource
     * @return int
     */
    public function saveCategory(Category $category,array $resource):int;

    /**
     * 更新分类
     * @param Category $category
     * @return mixed
     */
    public function updateCategory(Category $category,array $resources):bool;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.31 13:04
     * Describe 查询商品分类并把结果构建成树形结构
     * @param array $queryCondition
     * @param array $select
     * @return TreeVO
     */
    public function listCategoryByTree(array $queryCondition = [],array $select = []):TreeVO;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.16 10:44
     * Describe 查询分类
     * @param Category $category
     * @param int $page
     * @param int $size
     * @return array
     */
    public function listCategory(array $queryCondition):array;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.16 13:52
     * Describe
     * @param int $id
     * @param array $ids
     * @return bool
     */
    public function deleteCategoryById(int $id):bool;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.23 15:35
     * Describe 获取首页展示的商品分类
     * @return array
     */
    public function getIndexCategory():TreeVO;




}
