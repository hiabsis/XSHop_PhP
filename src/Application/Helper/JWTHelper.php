<?php
/**
 * @author     ：无畏泰坦
 * @date       ：Created in 2022.01.12 15:11
 * @description：JWT辅助类
 * @modified By：
 * @version:     1.0$
 */

namespace Application\Helper;
use Application\Constant\SystemConstants;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHelper
{
    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.12 15:49
     * @description： 加密
     * @modified By：
     * @version:     1.0$
     */
    public static function encode(array $payload,array $header=[]):string
    {
        return  JWT::encode($payload, SystemConstants::$HS256_KEY, SystemConstants::$HS256,head: $header);
    }

    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.12 15:51
     * @description：${description}
     * @modified By： 解密
     * @version:     1.0￥
     */
    public static function decode(string $jwt):object
    {
        return JWT::decode($jwt, new Key(SystemConstants::$HS256_KEY, 'HS256'));
    }
}