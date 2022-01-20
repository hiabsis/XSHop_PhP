<?php
/**
 * @author     ：无畏泰坦
 * @date       ：Created in 2022.01.19 11:16
 * @description：
 * @modified By：
 * @version:     1.0
 */

namespace Application\Service;

interface LoginServiceInterface
{
    public function authLogin(string $username,string $password):string;
}