<?php

namespace Application\Model;

use Application\Domain\Product\Product;
use Application\Domain\Product\ProductInfo;

interface ProductInfoModelInterFaceInterface extends BaseModelInterface
{

    /**
     * User: 无畏泰坦
     * Date: 2021.12.17 10:56
     * Describe  批量保存商品详情信息
     * @param array $productInfo
     * @return bool
     */
    public function saveProductInfoBatch(array $productInfo):bool;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.17 11:33
     * Describe  通过商品Id获取商品信息列表
     * @param $productId
     * @return array
     */
    public function listProductInfoByProductId($productId):array;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.17 13:22
     * Describe 通过商品Id删除商品关联信息
     * @param $productId
     * @return bool
     */
    public function removeProductInfoByProductId($productId):bool;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.22 10:17
     * Describe 更新商品Id
     * @param $productInfo
     * @return bool
     */
    public function updateProductInfo($productInfo) : bool;
}
