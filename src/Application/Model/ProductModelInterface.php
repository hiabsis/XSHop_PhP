<?php

namespace Application\Model;

use Application\Domain\Product\Product;

interface ProductModelInterface extends BaseModelInterface
{
    /**
     * User: 无畏泰坦
     * Date: 2021.12.17 11:18
     * Describe 通过商品的Id 获取商品
     * @param int $id
     * @return Product
     */
    public function getProductById(int $id):Product;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.21 16:01
     * Describe
     */
    public function findProduct(array $queryCondition,array $select,array $limit):array;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.22 9:09
     * Describe  统计查询的数量
     * @param Product $product
     * @return int
     */
    public function countProduct(array $queryCondition):int;

    public function removeProductByIds(array $ids):bool;
    public function updateProduct(Product $product):bool;
    public function saveProduct(Product $product):int;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.21 17:25
     * Describe 通过商品的Id批量获取商品信息
     * @param array $ids
     * @return  array  Product集合
     */
    public function listProductByIds(array  $ids) : array;
}
