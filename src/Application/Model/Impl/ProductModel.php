<?php

namespace Application\Model\Impl;

use Application\Domain\Product\Category;
use Application\Domain\Product\Product;
use Application\Exception\ModelException;
use Application\Exception\ModelValidatorParamsException;
use Application\Model\ProductModelInterface;
use Medoo\Medoo;
use PDO;

class ProductModel extends BaseModel implements ProductModelInterface
{

  public function __construct(PDO $conn, Medoo $medoo)
  {
      parent::__construct($conn, $medoo);
      $this->tableName = 'shop_product';
  }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.17 11:18
     * Describe 通过商品的Id 获取商品
     * @param int $id
     * @return Product
     */
    public function getProductById(int $id): Product
    {

        $arr = $this->findByCondition(where: [' id = ? '],binds:[$id],clazz: Product::class);
        if (empty($arr)) {
            throw new ModelException("数据库没有该条数据 FINDING BY ID => $id");
        }
        return $arr[0];
    }

    public function countProduct(array $queryCondition): int
    {
        $where = [];
        if (!empty($queryCondition)){
            $where = $this->buildQueryCondition($queryCondition);
        }
        return $this->medoo->count($this->tableName,$where);
    }

    public function findProduct(array $queryCondition, array $select = [],array $limit = [0,10]): array
    {


        // 查询条件
        $where = [];
        if (empty($select)){
            $select = "*";
        }
        if (!empty($queryCondition)){
            $where = $this->buildQueryCondition($queryCondition);
        }
        $where['LIMIT'] = $limit;
        return $this->medoo->select($this->tableName, $select,$where);
    }

    public function removeProductByIds(array $ids): bool
    {
        return $this->deleteIds($ids);
    }

    public function updateProduct(Product $product): bool
    {
        if (empty($product->id)) {
            return false;
        }
        return $this->update($product);
    }

    public function saveProduct(Product $product): int
    {
        return $this->insert($product);
    }

    public function listProductByIds(array $ids): array
    {
        $condition = [
            'clazz' => Product::class,
            'in' => ' id in ',
            'binds' => $ids
        ];
        return $this->findByCondition();
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.29 16:59
     * Describe 构建查询条件
     * @param array $queryCondition
     * @param array $where
     * @return array
     */
    protected function buildQueryCondition(array $queryCondition): array
    {
        $where = [];
        if (empty($queryCondition)){
            return [];
        }
        foreach ($queryCondition as $filed => $condition) {
            if (!empty($condition)) {
                if ($filed === 'id') {
                    $where['id'] = (string)$condition;
                } else if ($filed === 'userId') {
                    $where['user_id'] = $condition;
                } else if ($filed === 'categoryId') {
                    $where['category_id'] = $condition;
                } else if ($filed === 'productName') {
                    $where['name[~]'] = $condition;
                } else if ($filed === 'productPrice') {
                    $where['price'] = $condition;
                } else if ($filed === 'productDesc') {
                    $where['desc[~]'] = $condition;
                }
            } else {
                throw  new ModelValidatorParamsException("查询条件为空");
            }
        }
        return $where;
    }
}
