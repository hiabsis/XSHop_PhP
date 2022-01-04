<?php
/**
 * User: 无畏泰坦
 * Date: 2021.12.20 11:19
 * Describe
 */

namespace Application\Exception;


use PHPUnit\Exception;
use Psr\Cache\CacheException;

/**
 * Created on 2021.12.20 11:19
 * Created by 无畏泰坦
 * Describe 缓存持久化错误
 */
class CachePersistenceException extends BaseException implements CacheException
{

}
