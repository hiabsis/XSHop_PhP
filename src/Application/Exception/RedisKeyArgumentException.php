<?php
/**
 * User: 无畏泰坦
 * Date: 2021.12.20 10:15
 * Describe
 */

namespace Application\Exception;



use Psr\Cache\InvalidArgumentException;

/**
 * Created on 2021.12.20 10:15
 * Created by 无畏泰坦
 * Describe
 */
class RedisKeyArgumentException extends BaseException implements InvalidArgumentException
{

}
