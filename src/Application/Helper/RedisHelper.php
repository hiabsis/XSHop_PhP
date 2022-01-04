<?php
/**
 * User: 无畏泰坦
 * Date: 2021.12.20 9:21
 * Describe
 */

namespace Application\Helper;

use Application\Exception\RedisHelperParamsException;

/**
 * Created on 2021.12.20 9:21
 * Created by 无畏泰坦
 * Describe  redis 客户端
 */
class RedisHelper
{
    private $redis;
    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.21 16:36
     * Describe 检查是redis否存在键值
     * @param string $key
     * @return bool 存在key返回TRUE
     */
    public function existKey(string $key):bool
    {
        return $this->redis->exists($key);
    }
    /**
     * User: 无畏泰坦
     * Date: 2021.12.21 14:31
     * Describe 将集合$key中元素$value的score值加$score
     * @param string $key
     * @param string $value
     * @param int $score
     * @return bool 改变权重成功返回true 失败
     * @throws RedisHelperParamsException 当传入子程序的参数 key与value为空，自增权重为非自然数的时候抛出异常
     */
    public function zIncrBy(string $key, string $value, int $score = 1): bool
    {
        if (empty($key) || empty($value)){
            throw  new RedisHelperParamsException('执行有序集合元素自增错误 key or value is empty');
        }
        if ($score <= 0){
            throw  new RedisHelperParamsException('执行有序集合元素自增错误 score 必须是非负数');
        }
        $this->redis->zIncrBy($key, $score, $value);
        return true;

    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.20 11:53
     * Describe 设置redis中键的过期时间
     * @param string $key 键值
     * @param int $time 键值过期时间,单位秒,默认一小时过期
     * @return bool
     *
     */
    public function expire(string $key ,int $time ):bool
    {
        if (empty($key)){
            throw new RedisHelperParamsException('设置redis建过期,传入的建为空');
        }
        if(empty($time)){
            $time = $this->getDefaultTimeOut();
        }
        if ($time <=0){
            throw new RedisHelperParamsException('设置redis建过期,过期时间不为空');
        }
        return $this->redis->expire($key, $time);
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.20 11:53
     * Describe 设置redis中键的过期时间
     * @param string $key 键值
     * @param int $time 键值过期时间,单位秒,默认一小时过期
     * @return bool
     */
    public function expireAt(string $key ,int $time =0):bool
    {
        if (empty($key)){
            throw new RedisHelperParamsException('设置redis建过期,传入的建为空');
        }
        if (empty($time)){
            $time = $this->getDefaultTimeOutAtTomorrow();
        }
        if ($time <=0){
            throw new RedisHelperParamsException('设置redis建过期,过期时间不为空');
        }
        return $this->redis->expireAt($key, $time);
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.21 15:05
     * Describe 获取默认过期时间点 第二天失效
     * @return int
     */
    private  function getDefaultTimeOutAtTomorrow() : int
    {
        return mktime(23, 59, 59, date("m"), date("d"), date("Y"));
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.21 15:08
     * Describe 获取默认的键的过期时间
     * @return int
     */
    private function getDefaultTimeOut() : int {
        return  3600;
    }
    /**
     * User: 无畏泰坦
     * Date: 2021.12.21 15:00
     * Describe 获取集合中排名降序
     * @param string $key
     * @param int $start
     * @param int $end
     */
    public function zRevRange(string $key ,int $start,int $end = -1) : array
    {
        if (empty($key)){
            throw new RedisHelperParamsException("获取集合中排名降序 参数key为空");
        }

       return $this->redis->zRevRange($key, $start, $end);
    }




}
