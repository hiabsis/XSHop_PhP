<?php
/**
 * Collect: 无畏泰坦
 * Date: 2021.12.15 17:11
 * Describe
 */

namespace Application\Model\Impl;

use Application\Domain\System\Collect;
use Application\Exception\ModelValidatorParamsException;
use Application\Model\CollectionModelInterface;
use Medoo\Medoo;
use PDO;

/**
 * Created on 2021.12.15 17:11
 * Created by 无畏泰坦
 * Describe
 */
class CollectionModel extends BaseModel implements CollectionModelInterface
{
    public function __construct(PDO $conn, Medoo $medoo)
    {
        parent::__construct($conn, $medoo);
        $this->tableName = 'shop_product_collection';
    }


    /**
     * Collect: 无畏泰坦
     * Date: 2022.01.07 11:18
     * Describe 查询用户
     * @param array $select
     * @param array $queryCondition
     * @param array $limit
     * @param bool $isAnd
     * @return array
     */
    public function findCollect(array $select = [], array $queryCondition = [], array $limit = [], bool $isAnd = true): array
    {
        if (empty($select)) {
            $select = '*';
        }
        $where = [];
        if (!$isAnd) {
            $where['OR'] = $this->buildQueryCondition($queryCondition);
        } else {
            $where = $this->buildQueryCondition($queryCondition);
        }
        if (!empty($limit)) {
            $where['LIMIT'] = $limit;
        }
        return $this->medoo->select($this->tableName, $select, $where);

    }

    public function updateCollect(array $updateCollect, int $CollectId, array $menuIds): bool
    {
        if (array_key_exists('id', $updateCollect)) {
            throw  new ModelValidatorParamsException("非法参数 id,更新数据不能包含Id");
        }
        if (empty($updateCollect)) {
            return false;
        }
        $where['id'] = $CollectId;
        $stmt = $this->medoo->update($this->tableName, $updateCollect, $where);
        if ($stmt === null) {
            return false;
        }
        return $stmt->rowCount() > 0;
    }

    public function removeCollect(array $ids): bool
    {
        $stmt =   $this->medoo->delete($this->tableName,['id'=>$ids]);
        if ($stmt === null) {
            return false;
        }
        return $stmt->rowCount() > 0;
    }

    public function saveCollect(array $collect): int
    {
         $this->medoo->insert($this->tableName, $collect);
        return $this->medoo->id();
    }

    public function getCollect(array $select=[],array $query = []): array
    {
        if (empty($select)){
            $select = '*';
        }
        $res =   $this->medoo->get($this->tableName,$select,$query);
        return  empty($res) ? [] :$res ;
    }

    public function countCollect(array $query = []): int
    {
        $where = $this->buildQueryCondition($query);
       return $this->medoo->count($this->tableName,$where);
    }

    /**
     * Collect: 无畏泰坦
     * Date: 2021.12.29 16:59
     * Describe 构建查询条件
     * @param array $queryCondition
     * @return array
     */
    protected function buildQueryCondition(array $queryCondition): array
    {
        $where = [];
        if (empty($queryCondition)) {
            return [];
        }
        foreach ($queryCondition as $filed => $condition) {
            if (!empty($condition)) {
                switch ($filed) {
                    case 'id':
                    case 'user_id':
                    case 'product_id':
                        $where[$filed] = $condition;
                }
            } else {
                throw  new ModelValidatorParamsException("查询条件为空");
            }
        }
        return $where;
    }
}
