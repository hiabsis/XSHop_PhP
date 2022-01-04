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
     * Date: 2021.12.15 16:55
     * Describe 用户登入检验
     * @param User $user
     * @return User 返回完整的用户信息
     */
    public function login(User $user) : User;
}
