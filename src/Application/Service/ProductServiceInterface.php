<?php

namespace Application\Service;

use Application\Domain\Product\Product;
use JetBrains\PhpStorm\ArrayShape;

interface ProductServiceInterface
{
    /**
     * User: 无畏泰坦
     * Date: 2021.12.22 10:11
     * Describe 更新商品详情
     * @param Product $product
     * @param array $resources
     * @param array $productInfo
     * @return bool
     */
    public function updateProductDetail(Product $product, array $resources, array $productInfo):bool;
    /**
     * User: 无畏泰坦
     * Date: 2021.12.17 11:00
     * Describe 保存商品详情
     * @param Product $product
     * @param array $resources
     * @param array $productInfo
     * @return mixed
     */
    public function saveProductDetail(Product $product, array $resources, array $productInfo):bool;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.17 13:11
     * Describe 获取商品详情
     * @param int $productId
     * @return array
     */
    public function getProductDetail(int $productId):array;


    /**
     * User: 无畏泰坦
     * Date: 2021.12.17 13:11
     * Describe 删除商品
     * @param int $id
     * @return bool
     */
    public function removeProductById(int $id):bool;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.21 16:59
     * Describe 获取最热商品排名
     * @param int $start 排名开始
     * @param int $end  排名的结尾
     * @return array
     */
    public function listProductByHot(int $start,int $end):array;



    /**
     * User: 无畏泰坦
     * Date: 2021.12.22 17:21
     * Describe 查找商品信息
     * @param array $queryCondition
     * @param array $limit
     * @return array
     */
    #[ArrayShape(['total' => "int", 'data' => "array"])] public function listProduct(array $queryCondition,array $limit ):array;



    /**
     * User: 无畏泰坦
     * Date: 2021.12.22 15:06
     * Describe 获取首页轮播图
     * @return array  [ [productId : id , imgUrl :imgAccessPatch]]
     */
    public function getBannerProduct() : array;




}
