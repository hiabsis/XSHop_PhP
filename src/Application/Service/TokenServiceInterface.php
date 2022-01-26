<?php
/**
 * @author     ：无畏泰坦
 * @date       ：Created in 2022.01.19 11:22
 * @description：
 * @modified By：
 * @version:     1.0
 */

namespace Application\Service;

interface TokenServiceInterface
{
    public function generateToken(array $user): string;

    public function getUserInfo(string $token):array;

    /**
     * 退出登录时,将token置为无效
     */
    public function invalidateToken(string $token);


    public function checkPermission(string $url,string $token):bool;

    public function isOpenUrl(string $url);
}