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
use Application\Model\ApiModelInterface;
use Medoo\Medoo;
use PDO;

class ApiModel extends BaseModel implements ApiModelInterface
{
    private $redis;

    public function __construct(PDO $conn, Medoo $medoo, RedisHelper $redis)
    {
        parent::__construct($conn, $medoo);
        $this->tableName = 'sys_api';
        $this->redis = $redis;
    }
    public function countApi(array $queryCondition = [])
    {
        $where = $this->buildQueryCondition($queryCondition);
        return $this->medoo->count($this->tableName, $where);
    }

    public function findApi(array $select = [], array $queryCondition = [], array $limit = [], bool $isAnd = true): array
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

    public function getApi(array $select = [], array $queryCondition = [])
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
     * @param int $RoleId
     * @return bool
     */
    public function updateApiById(array $updateDate = [], int $RoleId = 0): bool
    {
        if (array_key_exists('id', $updateDate)) {
            throw  new ModelValidatorParamsException("非法参数 id,更新数据不能包含Id");
        }
        if (empty($updateDate)) {
            return false;
        }
        $where['id'] = $RoleId;
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
    public function removeApiByIds(array $ids = []): bool
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
    public function saveApi(array $saveData): bool
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
                    case 'permission':
                    case 'status':
                    case 'path':
                    case 'type':

                        $where[$filed] = $condition;
                }

        }
        $where["ORDER"] = [
            'name'  => "DESC",
        ];
        return $where;
    }
}