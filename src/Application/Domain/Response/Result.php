<?php

namespace Application\Domain\Response;


/**
 * 请求的结果
 */
class Result
{

    public static function SUCCESS($data=null,$message="success"): array
    {
        return ['data' => $data,'message' => $message , 'code' => 200];
    }
    public static function FAIL($data=null,$message="error"): array
    {
        return ['data' => $data,'message' => $message , 'code' => 500];
    }
}
