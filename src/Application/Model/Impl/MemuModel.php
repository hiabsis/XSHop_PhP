<?php
/**
 * User: 无畏泰坦
 * Date: 2022.01.05 16:34
 * Describe
 */

namespace Application\Model\Impl;

use Application\Exception\ModelValidatorParamsException;
use Application\Model\MenuModelInterface;
use Medoo\Medoo;
use PDO;

/**
 * Created on 2022.01.05 16:34
 * Created by 无畏泰坦
 * Describe
 */
class MemuModel extends BaseModel implements MenuModelInterface
{

    public function __construct(PDO $conn, Medoo $medoo)
    {
        parent::__construct($conn, $medoo);
        $this->tableName = 'sys_menu';
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 16:43
     * Describe
     * @param array $select
     * @param array $queryCondition
     * @param array $limit
     * @param bool $isAnd
     * @return array
     */
    public function findMenu(array $select = [],array $queryCondition = [],array $limit = [],bool $isAnd = true): array
    {
        if (empty($select)) {
            $select = '*';
        }
        $where = [];
        if (!$isAnd){
            $where['OR'] = $this->buildQueryCondition($queryCondition);
        }else{
            $where= $this->buildQueryCondition($queryCondition);
        }
        if (!empty($limit)){
            $where['LIMIT'] = $limit;
        }
        return $this->medoo->select($this->tableName, $select,$where);
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 17:01
     * Describe 批量删除
     * @param array $ids
     * @return bool
     */
    public function removeMenuByIds(array $ids = []): bool
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
     * Date: 2022.01.05 17:01
     * Describe 更新数据
     * @param array $updateDate
     * @param int $menuId
     * @return bool
     */
    public function updateMenuById(array $updateDate = [], int $menuId = 0): bool
    {
        if (array_key_exists('id', $updateDate)) {
            throw  new ModelValidatorParamsException("非法参数 id,更新数据不能包含Id");
        }
        if (empty($updateDate)) {
            return false;
        }
        $where['id'] = $menuId;
        $stmt = $this->medoo->update($this->tableName, $updateDate, $where);
        if ($stmt === null) {
            return false;
        }
        return $stmt->rowCount() > 0;
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 17:35
     * Describe 保存菜单
     * @param $saveData
     * @return int
     */
    public function saveMenu($saveData): int
    {
        $this->medoo->insert($this->tableName, $saveData);
        return  $this->medoo->id();
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 17:36
     * Describe 统计数据
     * @param array $queryCondition
     * @return int
     */
    public function countMenu(array $queryCondition):int
    {
        $where = $this->buildQueryCondition($queryCondition);
        return $this->medoo->count($this->tableName,$where);
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 17:28
     * Describe 是否存在
     * @param string $name
     * @return bool
     */
    public function hasMenu(string $name = ''):bool
    {
        $where = [];
        if (empty($name)){
            return  false;
        }
        $where['name'] = $name;
        return $this->medoo->has($this->tableName,$where);
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
        if (empty($queryCondition)){
            return [];
        }
        foreach ($queryCondition as $filed => $condition) {
            if (!empty($condition)) {
                switch ($filed)
                {
                    case 'name_zh':
                    case 'path':
                    case 'name':
                    case 'component':
                        $where[$filed.'[~]'] = $condition;
                }
            } else {
                throw  new ModelValidatorParamsException("查询条件为空");
            }
        }
        return $where;
    }

}
