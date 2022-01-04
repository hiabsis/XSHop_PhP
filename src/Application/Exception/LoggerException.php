<?php


namespace Application\Exception;

/**
 * Created on 2021.12.15 15:19
 * Created by 无畏泰坦
 * Describe 继承该异常的类，实现errorInfo 输出一条日志记录
 */
interface LoggerException
{
    /**
     * User: 无畏泰坦
     * Date: 2021.12.15 15:21
     * Describe 输出一条日志记录
     * @return string
     */
    public function getLoggerInfo():array;
}
