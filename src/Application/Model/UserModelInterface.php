<?php

namespace Application\Model;

use Application\Domain\System\User;

interface UserModelInterface extends BaseModelInterface
{


    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 9:23
     * Describe 查找用户
     * @param array $select
     * @param array $queryCondition
     * @param array $limit
     * @param bool $isAnd
     * @return mixed
     */
    public function findUser(array $select = [], array $queryCondition = [], array $limit = [], bool $isAnd = true):array;

    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 11:16
     * Describe  批量删除
     * @param array $ids
     * @return bool
     */
    public function removeUserByIds(array $ids = []): bool;

    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 17:01
     * Describe 更新数据
     * @param array $updateDate
     * @param int $userId
     * @return bool
     */
    public function updateUserById(array $updateDate = [], int $userId = 0): bool;

    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 11:01
     * Describe 统计数据
     * @param array $query
     * @return int
     */
    public function countUser(array $query): int;

    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 10:59
     * Describe 是否存在
     * @param string $username 用户名
     * @return bool
     */
    public function hasUser(string $username = ''): bool;
    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 9:26
     * Describe 保存用户信息
     * @param array $saveData
     * @return bool
     */
    public function saveUser(array $saveData):bool;

    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 11:36
     * Describe 获取单个用户
     * @param array $query
     * @return array
     */
    public function getUser(array $query): array;


    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.12 16:20
     * @description：缓存用户信息
     * @modified By：
     * @version:     $version$
     */
    public function cacheUserInfo(array $user,string $token);

    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.13 11:38
     * @description：获取缓存的用户信息
     * @modified By：
     * @version:     1.0
     */
    public function getCacheUserInfo(string $token);

    public function removeMenu(array $ids);

    public function removeUserInfoCache(string $token);

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
    public function getUserDetailInfo(string $username) :array;
}
