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

        return $this->medoo->get($this->tableName,$select,$where);

    }

    public function cacheAllApi(string $key, array $data): bool
    {
        return $this->redis->setHash($key, $data);
    }

    public function getCacheApi(string $uri): array
    {
        return $this->redis->getHash($uri);
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
            if (!empty($condition)) {
                switch ($filed) {
                    case 'id':
                    case 'permission':
                        $where[$filed] = $condition;

                }
            } else {
                throw  new ModelValidatorParamsException("查询条件为空");
            }
        }
        return $where;
    }
}