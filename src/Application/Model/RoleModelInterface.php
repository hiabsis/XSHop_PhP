<?php

namespace Application\Model;

use Application\Domain\System\Role;

interface RoleModelInterface extends BaseModelInterface
{


    /**
     * Role: 无畏泰坦
     * Date: 2022.01.05 9:23
     * Describe 查找用户
     * @param array $select
     * @param array $queryCondition
     * @param array $limit
     * @param bool $isAnd
     * @return mixed
     */
    public function findRole(array $select = [], array $queryCondition = [], array $limit = [], bool $isAnd = true):array;

    /**
     * Role: 无畏泰坦
     * Date: 2022.01.07 11:16
     * Describe  批量删除
     * @param array $ids
     * @return bool
     */
    public function removeRoleByIds(array $ids = []): bool;

    /**
     * Role: 无畏泰坦
     * Date: 2022.01.05 17:01
     * Describe 更新数据
     * @param array $updateDate
     * @param int $RoleId
     * @return bool
     */
    public function updateRoleById(array $updateDate = [], int $RoleId = 0): bool;

    /**
     * Role: 无畏泰坦
     * Date: 2022.01.07 11:01
     * Describe 统计数据
     * @param array $query
     * @return int
     */
    public function countRole(array $query): int;

    /**
     * Role: 无畏泰坦
     * Date: 2022.01.07 10:59
     * Describe 是否存在
     * @param string $name 用户名
     * @return bool
     */
    public function hasRole(string $name = ''): bool;
    /**
     * Role: 无畏泰坦
     * Date: 2022.01.05 9:26
     * Describe 保存用户信息
     * @param array $saveData
     * @return bool
     */
    public function saveRole(array $saveData):bool;

    /**
     * Role: 无畏泰坦
     * Date: 2022.01.07 11:36
     * Describe 获取单个用户
     * @param array $query
     * @return array
     */
    public function getRole(array $query): array;


}
