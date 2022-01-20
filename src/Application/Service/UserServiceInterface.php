<?php
/**
 * User: 无畏泰坦
 * Date: 2021.12.15 16:54
 * Describe
 */

namespace Application\Service;

use Application\Domain\System\User;
use Application\Domain\VO\TreeVO;
use JetBrains\PhpStorm\Pure;

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
     * Describe
     * @param array $ids
     * @return bool
     */
    public function removeMenu(array $ids ):bool;



    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 11:24
     * Describe 获取用户
     * @param string $username
     * @param string $password
     * @return array
     */
    public function getUser(string $username,string $password) : array;

    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 14:30
     * Describe
     * @param array $user
     * @param $userId
     * @return bool
     */
    public function updateUser(array $user,$userId):bool;

    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.12 16:28
     * @description：${description}
     * @modified By： 用户登入
     * @version:     1.0
     */
    public function login(string $username,string$password) : string;

    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.13 11:15
     * @description：${description}
     * @modified By：
     * @version:     1.0
     */
    public function checkJwtTokenInfo(string $jwtToken);

    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.13 14:16
     * @description：检查用户是否存在
     * @modified By：
     * @version:     1.0
     */
    public function isExist(string $username):bool;

    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.13 14:28
     * @description：${description}
     * @modified By： 用户注册
     * @version:     1.0
     */
    public function register(string $username,string $password):int;

    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.13 17:30
     * @description：${description}
     * @modified By：
     * @version:     1.0
     */
    public function logout(string $jwtToken): bool;


    /**
     * 加载当前用户的菜单
     */
    #[Pure] public function getMenusTreeByCurrentUserId(int $currentUserId):TreeVO;


    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.19 15:01
     * @description：${description}
     * @modified By：
     * @version:     1.0
     * @return  array => [
     *   int user_id  用户ID
     *   string username 用户名
     *   string nickname 用户昵称
     *   array role_ids  角色
     *   array menus     页面
     *   array permissions 权限
     * ]
     */
    public function getUserDetailInfo(int $userId) :array;
}
