<?php
/**
 * User: 无畏泰坦
 * Date: 2021.12.21 16:45
 * Describe
 */

namespace Application\Exception;



use Throwable;

/**
 * Created on 2021.12.21 16:45
 * Created by 无畏泰坦
 * Describe 查找商品信息 未查询到结果
 */
class ProductDateNotFind extends BaseException
{


    public function getErrorInfo(): array
    {
        $info =  parent::getErrorInfo(); // TODO: Change the autogenerated stub
        $info .= $this->getMessage();
        return  $info;
    }
}
