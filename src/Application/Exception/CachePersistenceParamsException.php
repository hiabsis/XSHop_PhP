<?php


namespace Application\Exception;


use Psr\Cache\CacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Created on 2021.12.20 13:04
 * Created by 无畏泰坦
 * Describe 缓存持久化 参数异常
 */
class CachePersistenceParamsException extends  \RuntimeException implements InvalidArgumentException
{

}
