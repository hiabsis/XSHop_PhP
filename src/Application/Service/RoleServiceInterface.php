<?php
/**
 * Role: 无畏泰坦
 * Date: 2021.12.15 16:54
 * Describe
 */

namespace Application\Service;

use Application\Domain\System\Role;

/**
 * Created on 2021.12.15 16:54
 * Created by 无畏泰坦
 * Describe 用户
 */
interface RoleServiceInterface
{


    /**
     * Role: 无畏泰坦
     * Date: 2022.01.05 17:43
     * Describe 分页查询菜单
     * @param array $query
     * @return array
     */
    public function listRoleByPage(array $query = []):array;


    /**
     * Role: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe 更新数据
     * @param array $updateRole
     * @param int $roleId
     * @return bool
     */
    public function updateRole(array $updateRole, int $roleId, array $menuIds):bool;

    /**
     * Role: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe
     * @param array $ids
     * @return bool
     */
    public function removeRole(array $ids ):bool;

    /**
     * Role: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe 数据保存
     * @param array $Role
     * @return int
     */
    public function saveRole(array $Role):int;

    /**
     * Role: 无畏泰坦
     * Date: 2022.01.07 11:24
     * Describe 获取用户
     * @param string $name
     * @param string $password
     * @return array
     */
    public function getRole(string $name,string $password) : array;

}
