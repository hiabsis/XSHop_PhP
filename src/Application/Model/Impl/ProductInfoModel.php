<?php

namespace Application\Model\Impl;

use Application\Domain\Product\Product;
use Application\Domain\Product\ProductInfo;
use Application\Exception\ModelException;
use Application\Exception\ModelValidatorParamsException;
use Application\Model\ProductInfoModelInterFace;
use Medoo\Medoo;
use PDO;

class ProductInfoModel extends BaseModel implements ProductInfoModelInterFace
{

   public function __construct(PDO $conn, Medoo $medoo)
   {
       parent::__construct($conn, $medoo);
       $this->tableName = 'shop_product_info';
   }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.17 11:37
     * Describe 批量保存商品规格表
     * @param array $productInfo
     * @return bool
     */
    public function saveProductInfoBatch(array $productInfo): bool
    {
        // 参数类型校验
        foreach ($productInfo as $info) {
            if (!$info instanceof ProductInfo) {
                throw new ModelException(' params $productInfo require ' . ProductInfo::class . ' but get ' . gettype($info), ModelException::ERROR_PARAMS);
            }
        }
        return $this->insertBatch($productInfo);
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.17 11:33
     * Describe  通过商品Id获取商品信息列表
     * @param $productId
     * @return array
     */
    public function listProductInfoByProductId($productId): array
    {
        return $this->findByCondition( where: [' product_id = ? '], binds: [$productId],  clazz:  ProductInfo::class);
    }
    /**
     * User: 无畏泰坦
     * Date: 2021.12.17 13:22
     * Describe 通过商品Id删除商品关联信息
     * @param $productId
     * @return bool
     */
    public function removeProductInfoByProductId($productId): bool
    {
        return $this->deleteByCondition([
            'where' => [' product_id = ? '],
            'binds' => [$productId],
        ]);
    }
    /**
     * User: 无畏泰坦
     * Date: 2021.12.22 10:17
     * Describe 更新商品Id
     * @param $productInfo
     * @return bool
     */
    public function updateProductInfo($productInfo): bool
    {
        if (empty($productInfo->id)) {
            throw new ModelValidatorParamsException("更新商品详情时Id为空");
        }
        return $this->update($productInfo);
    }
}
