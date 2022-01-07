<?php
/**
 * User: 无畏泰坦
 * Date: 2021.12.15 16:29
 * Describe
 */

namespace Application\Domain\System;

use Application\Domain\BaseDomain;

/**
 * Created on 2021.12.15 16:29
 * Created by 无畏泰坦
 * Describe
 */
class User extends BaseDomain
{
    public $id;
    public $username;
    public $password;
    public $salt;

}
