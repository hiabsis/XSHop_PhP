<?php

namespace Application\Exception;

use Throwable;

/**
 * 参数校验失败
 */
class ValidatorParamsException extends BaseException
{


    public function getErrorInfo(): array
    {
        $info =  parent::getErrorInfo(); // TODO: Change the autogenerated stub
        $info['msg'] .= ' ' . $this->getMessage();
        return $info;
    }
}
