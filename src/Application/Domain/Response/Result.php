<?php

namespace Application\Domain\Response;


/**
 * 请求的结果
 */
class Result
{

    public static function SUCCESS($data=[],$message="success"): array
    {
        return ['data' => $data,'message' => $message , 'code' => 200];
    }
    public static function FAIL($data=[],$message="error"): array
    {
        return ['data' => $data,'message' => $message , 'code' => 500];
    }
}
