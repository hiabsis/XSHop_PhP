<?php

namespace Application\Model;

use Application\Domain\System\User;

interface UserModelInterfaceInterface extends BaseModelInterface
{

    /**
     * User: 无畏泰坦
     * Date: 2021.12.15 17:09
     * Describe 获取一条用户信息
     * @param User $user 查询条件 and
     * @return User
     */
    public function getOneByUser(User $user) : User;
}
