<?php
/**
 * User: 无畏泰坦
 * Date: 2021.12.15 16:55
 * Describe
 */

namespace Application\Service\System;

use Application\Domain\System\User;
use Application\Exception\ModelException;
use Application\Exception\ServiceException;
use Application\Model\UserModelInterfaceInterface;
use Application\Service\UserServiceInterface;

/**
 * Created on 2021.12.15 16:55
 * Created by 无畏泰坦
 * Describe
 */
class UserService implements UserServiceInterface
{
    private $userModel;
    public function __construct(UserModelInterfaceInterface $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.15 16:56
     * Describe 用户登入服务
     * @param User $user
     * @return mixed|void
     */
    public function login(User $user)  : User
    {
        $user = $this->userModel->getOneByUser($user);
        if (empty($user->username) || empty($user->password)){
            throw  new ServiceException(" 用户登入失败");
        }
        return $user;

    }
}
