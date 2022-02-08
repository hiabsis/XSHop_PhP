<?php
/**
 * Role: 无畏泰坦
 * Date: 2021.12.15 17:11
 * Describe
 */

namespace Application\Model\Impl;

use Application\Domain\System\Role;
use Application\Exception\ModelValidatorParamsException;
use Application\Model\RoleModelInterface;
use Medoo\Medoo;
use PDO;

/**
 * Created on 2021.12.15 17:11
 * Created by 无畏泰坦
 * Describe
 */
class RoleModel extends BaseModel implements RoleModelInterface
{
    public function __construct(PDO $conn, Medoo $medoo)
    {
        parent::__construct($conn, $medoo);
        $this->tableName = 'sys_role';
    }

    /**
     * Role: 无畏泰坦
     * Date: 2022.01.07 11:18
     * Describe 查询用户
     * @param array $select
     * @param array $queryCondition
     * @param array $limit
     * @param bool $isAnd
     * @return array
     */
    public function findRole(array $select = [], array $queryCondition = [], array $limit = [], bool $isAnd = true): array
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

    /**
     * Role: 无畏泰坦
     * Date: 2022.01.07 11:16
     * Describe  批量删除
     * @param array $ids
     * @return bool
     */
    public function removeRoleByIds(array $ids = []): bool
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
     * Date: 2022.01.05 17:01
     * Describe 更新数据
     * @param array $updateDate
     * @param int $RoleId
     * @return bool
     */
    public function updateRoleById(array $updateDate = [], int $RoleId = 0): bool
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
     * Date: 2022.01.07 11:18
     * Describe 保存用户
     * @param array $saveData
     * @return int
     */
    public function saveRole(array $saveData): int
    {
        $this->medoo->insert($this->tableName, $saveData);
        return $this->medoo->id();
    }

    /**
     * Role: 无畏泰坦
     * Date: 2022.01.07 11:01
     * Describe 统计数据
     * @param array $query
     * @return int
     */
    public function countRole(array $query): int
    {
        $where = $this->buildQueryCondition($query);
        return $this->medoo->count($this->tableName, $where);
    }

    /**
     * Role: 无畏泰坦
     * Date: 2022.01.07 10:59
     * Describe 是否存在
     * @param string $name 用户名
     * @return bool
     */
    public function hasRole(string $name = ''): bool
    {
        $where = [];
        if (empty($name)) {
            return false;
        }
        $where['name'] = $name;
        return $this->medoo->has($this->tableName, $where);
    }

    /**
     * Role: 无畏泰坦
     * Date: 2022.01.07 11:36
     * Describe 获取单个用户
     * @param array $query
     * @param array $select
     * @return array
     */
    public function getRole(array $query,array $select = []): array
    {
        if (empty($select)){
            $select = '*';
        }
       return  $this->medoo->get($this->tableName,$select,$query);
    }


    /**
     * Role: 无畏泰坦
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
                    case 'name':
                        $where[$filed] = $condition;
                }
            } else {
                throw  new ModelValidatorParamsException("查询条件为空");
            }
        }
        return $where;
    }
}
