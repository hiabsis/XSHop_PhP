<?php
/**
 * @author     ：无畏泰坦
 * @date       ：Created in 2022.01.19 11:42
 * @description： 本系统使用的自定义错误类
 * 比如在校验参数时,如果不符合要求,可以抛出此错误类
 * @modified By：
 * @version:     1.0
 */

namespace Application\Exception;


use Throwable;

class CommonException extends BaseException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null,array $errorInfo = [])
    {
        $this.$this->setErrorInfo($errorInfo);
        parent::__construct($message, $code, $previous);
    }
}