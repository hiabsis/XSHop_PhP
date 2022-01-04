<?php

namespace Application\Exception;

use Throwable;

abstract class BaseException extends \RuntimeException implements LoggerException
{

    /**
     * @var array  返回给前端的异常信息
     */
    protected $error = [
        'status' => 500,
        'code' => 100000,
        'msg' => '系统异常'
    ];

    /**
     * @var array  需要记录的异常信息
     */
    protected $reportInfo = [
        'code' => 100000,
        'position' => '未知',
        'msg' => '系统异常',
    ];



    /**
     * User: 无畏泰坦
     * Date: 2021.12.20 17:09
     * Describe 获取异常信息
     * 返回一个数组携带异常信息，异常信息包含信息
     * [status => 请求状态码 ，code => 错误码 ，'msg' => 错误提示信息]
     * 该异常的定义
     */
    public function getErrorInfo(): array
    {
        $info = SystemErrorInfo::getErrorInfo($this);
        if (!empty($info)){
            return  $info;
        }
        return $this->error;
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.20 17:14
     * Describe
     * 返回一个数组携带日志信息，日志信息包含信息
     * [position => 异常抛出位置 ，code => 错误码 ，'msg' => 错误提示信息]
     * @return array
     */
    public function getLoggerInfo(): array
    {
        $info = SystemErrorInfo::getErrorLogInfo($this);
        if (!empty($info)){
            return  $info;
        }
        return $this->reportInfo;
    }
}
