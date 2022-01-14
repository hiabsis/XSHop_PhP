<?php


namespace Application\Model\Impl;

use Application\Domain\System\UserRole;
use Application\Exception\ModelValidatorParamsException;
use Application\Model\RoleMenuModelInterface;
use Application\Model\RoleModelInterface;
use Application\Model\UserModelInterface;
use Application\Model\UserRoleModelInterface;
use Medoo\Medoo;
use PDO;

/**
 * Created on 2021.12.15 17:11
 * Created by 无畏泰坦
 * Describe
 */
class RoleMenuModel extends BaseModel implements RoleMenuModelInterface
{
    public function __construct(PDO $conn, Medoo $medoo)
    {
        parent::__construct($conn, $medoo);
        $this->tableName = 'sys_role_menu';
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 11:18
     * Describe 查询用户
     * @param array $select
     * @param array $queryCondition
     * @param array $limit
     * @param bool $isAnd
     * @return array
     */
    public function findRoleMenu(array $select = [], array $queryCondition = [], array $limit = [], bool $isAnd = true): array
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
     * User: 无畏泰坦
     * Date: 2022.01.07 11:16
     * Describe  批量删除
     * @param array $ids
     * @return bool
     */
    public function removeRoleMenuByIds(array $ids = []): bool
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
     * User: 无畏泰坦
     * Date: 2022.01.07 11:18
     * Describe 保存用户
     * @param array $saveData
     * @return bool
     */
    public function saveRoleMenu(array $saveData): bool
    {
        $this->medoo->insert($this->tableName, $saveData);
        return $this->medoo->id();
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

             if (($condition)) {
                switch ($filed) {
                    case 'id':
                    case 'menu_id':
                    case 'role_id':
                        $where[$filed] = $condition;
                }
            } else {
                throw  new ModelValidatorParamsException("查询条件为空");
            }
        }
        return $where;
    }
}
