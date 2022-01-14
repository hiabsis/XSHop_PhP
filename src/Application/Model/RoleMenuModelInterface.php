<?php

namespace Application\Model;

use Application\Domain\System\UserRole;

interface RoleMenuModelInterface extends BaseModelInterface
{


    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 9:23
     * Describe
     * @param array $select
     * @param array $queryCondition
     * @param array $limit
     * @param bool $isAnd
     * @return mixed
     */
    public function findRoleMenu(array $select = [], array $queryCondition = [], array $limit = [], bool $isAnd = true):array;

    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 11:16
     * Describe  批量删除
     * @param array $ids
     * @return bool
     */
    public function removeRoleMenuByIds(array $ids = []): bool;



    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 9:26
     * Describe 保存用户信息
     * @param array $saveData
     * @return bool
     */
    public function saveRoleMenu(array $saveData):bool;



}
