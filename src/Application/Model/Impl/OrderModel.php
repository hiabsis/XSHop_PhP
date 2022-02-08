<?php
/**
 * @author     ：无畏泰坦
 * @date       ：Created in 2022.01.19 15:17
 * @description：
 * @modified By：
 * @version:     1.0
 */

namespace Application\Model\Impl;

use Application\Exception\ModelValidatorParamsException;
use Application\Helper\RedisHelper;

use Medoo\Medoo;
use PDO;

class OrderModel extends BaseModel implements \Application\Model\OrderModel
{
    private $redis;

    public function __construct(PDO $conn, Medoo $medoo, RedisHelper $redis)
    {
        parent::__construct($conn, $medoo);
        $this->tableName = 'shop_order';
        $this->redis = $redis;
    }
    public function countOrder(array $queryCondition = []): ?int
    {
        $where = $this->buildQueryCondition($queryCondition);
        return $this->medoo->count($this->tableName, $where);
    }

    public function findOrder(array $select = [], array $queryCondition = [], array $limit = [], bool $isAnd = true): array
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

    public function getOrder(array $select = [], array $queryCondition = [])
    {
        if (empty($select)) {
            $select = '*';
        }
        $where = $this->buildQueryCondition($queryCondition);

        $api =  $this->medoo->get($this->tableName,$select,$where);
        return $api ?? [];

    }

    /**
     * Role: 无畏泰坦
     * Date: 2022.01.05 17:01
     * Describe 更新数据
     * @param array $updateDate
     * @param int $orderId
     * @return bool
     */
    public function updateOrderById(array $updateDate = [], int $orderId = 0): bool
    {
        if (array_key_exists('id', $updateDate)) {
            throw  new ModelValidatorParamsException("非法参数 id,更新数据不能包含Id");
        }
        if (empty($updateDate)) {
            return false;
        }
        $where['id'] = $orderId;
        $stmt = $this->medoo->update($this->tableName, $updateDate, $where);
        if ($stmt === null) {
            return false;
        }
        return $stmt->rowCount() > 0;
    }
    /**
     * Role: 无畏泰坦
     * Date: 2022.01.07 11:16
     * Describe  批量删除
     * @param array $ids
     * @return bool
     */
    public function removeOrderByIds(array $ids = []): bool
    {
        if (empty($ids)) {
            return true;
        }
        $where['id'] = $ids;
        $stmt = $this->medoo->delete($this->tableName, $where);
        if ($stmt === null) {
            return false;
        }
        return $stmt->rowCount() > 0;
    }
    /**
     * Role: 无畏泰坦
     * Date: 2022.01.07 11:18
     * Describe 保存用户
     * @param array $saveData
     * @return bool
     */
    public function saveOrder(array $saveData): bool
    {
        $this->medoo->insert($this->tableName, $saveData);
        return $this->medoo->id();
    }
    public function cacheAllApi(string $key, array $data): bool
    {
        return $this->redis->setHash($key, $data);
    }

    public function getCacheApi(string $uri): array
    {
        $api =  $this->redis->getHash($uri);
        if (empty($api) || $api[0] === 0){
            return  [];
        }
        return $api;
    }

    /**
     * User: 无畏泰坦
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

                switch ($filed) {
                    case 'id':
                    case 'product_id':
                    case 'number':
                    case 'status':
                    case 'order_id':
                    case 'user_id':

                        $where[$filed] = $condition;
                }

        }
        $where["ORDER"] = [
            'order_id'  => "DESC",
        ];
        return $where;
    }
}