<?php

namespace Application\Helper;
use Application\Domain\Product\Category;
use Application\Domain\Product\Product;
use Application\Domain\Product\ProductInfo;
use Application\Domain\Product\ProductRelatedResource;
use Application\Domain\System\Resource;
use Application\Domain\System\User;
use Application\Exception\ClazzMapperException;


/**
 *
 */
class ClassHelper
{
    // 类属性与请求参数的映射
    private static  $mappers ;

    public static function setMapper(array  $mappers){
        self::$mappers = $mappers;
    }
    /**
     * 创建一个类
     * @param array $resource
     * @param $clazz
     * @return mixed
     */
    public static function newInstance(array $resource,$clazz ,bool $hasAttribute = true){
        $instence = new $clazz();
        $attribute = self:: $mappers[$clazz];
        if ($hasAttribute){
            foreach ($attribute as $key=>$value){
                if (array_key_exists($value,$resource)){
                    $instence->$key = $resource[$value];
                }
            }
        }else{
            foreach ($resource as $key=>$value){
                $instence->$key = $resource[$value];
            }
        }
        return $instence;
    }
    /**
     * 创建一个类的数组
     */
    public static function newInstanceArray(array $resource,$clazz,bool $hasAttribute = true)
    {
        $res = [];
        foreach ($resource as $value) {
            $res[] = self::newInstance($value, $clazz);
        }
        return $res;
    }
    /**
     * User: 无畏泰坦
     * Date: 2021.12.16 11:56
     * Describe 按照配置规则，把类属性映射cheng
     */
    public static function newArrayByObject(object $obj):array
    {
        $res = [];
        $clazz = new \ReflectionObject($obj);
        $clazz = $clazz->name;
        $rule = self::$mappers[$clazz];
        if (empty($rule)){
            throw new ClazzMapperException($clazz."未配置映射属性");
        }
        foreach ($rule as $key => $value){
            $res[$value] = $obj->$key;
        }
        return $res;
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.16 13:02
     * Describe 安装配置规则，把类实例数组抓换成索引数组
     */
    public static function newArrayByObjectArr(array $arr) : array
    {
        if (empty($arr)){
            return [];
        }
        $res = [];
        foreach ($arr as  $item){
            $res[] = self::newArrayByObject($item);
        }
        return $res;
    }

}
