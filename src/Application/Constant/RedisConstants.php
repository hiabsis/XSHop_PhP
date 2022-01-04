<?php
namespace Application\Constant;


/**
 * Created on 2021.12.28 15:50
 * Created by 无畏泰坦
 * Describe redis 常量
 */
class RedisConstants
{
    // 热门商品的在redis中的键值 记录一天的
    public static $PRODUCT_HOT_KEY = 'PRODUCT_HOST';
    // 热门商品在redis中的键值;
    public static $REDIS_HOT_PRODUCT_KEY = 'hot_product';
    // redis建默认过期时间
    public static $REDIS_HOT_PRODUCT_KEY_TIME_OUT= 60*60*24;
}
