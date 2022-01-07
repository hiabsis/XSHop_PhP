<?php
namespace  Application\Domain;

use Application\Helper\ClassHelper;

/**
 * Created on 2022.01.05 9:16
 * Created by 无畏泰坦
 * Describe
 */
class BaseDomain
{
    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 9:18
     * Describe 获取对象数组
     */
    public static function getObjectArrary(){
//        return ClassHelper::newArrayByObject();
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 11:04
     * Describe 创建一个对象
     * @param array $resource
     * @return object
     */
    public static function builderObject(array $resource):object
    {
        return \Application\Helper\ClassHelper::newInstance($resource,self::class);
    }
}
