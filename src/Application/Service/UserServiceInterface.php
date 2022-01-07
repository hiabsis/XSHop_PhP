<?php
/**
 * User: 无畏泰坦
 * Date: 2021.12.15 16:54
 * Describe
 */

namespace Application\Service;

use Application\Domain\System\User;

/**
 * Created on 2021.12.15 16:54
 * Created by 无畏泰坦
 * Describe 用户
 */
interface UserServiceInterface
{


    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 17:43
     * Describe 分页查询菜单
     * @param array $query
     * @return array
     */
    public function listUserByPage(array $query = []):array;


    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe 更新数据
     * @param array $updateUser
     * @param int $userId
     * @return bool
     */
    public function updateMenu(array $updateUser, int $userId):bool;

    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe
     * @param array $ids
     * @return bool
     */
    public function removeMenu(array $ids ):bool;

    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe 数据保存
     * @param array $user
     * @return int
     */
    public function saveUser(array $user):int;

    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 11:24
     * Describe 获取用户
     * @param string $username
     * @param string $password
     * @return array
     */
    public function getUser(string $username,string $password) : array;
}
