<?php
namespace Application\Constant;

/**
 * Created on 2021.12.28 15:47
 * Created by 无畏泰坦
 * Describe 系统常量
 */
class SystemConstants
{
    // 上传保存文件的最大值
    public static $FILE_MAX_SIZE = 1024*1024*3;
    // 用户信息的过期时间
    public static $REDIS_USER_INFO_TIME_OUT = 60*60*24*3;
    // 文件访问的前缀
    public static $FILE_ACCESS_PATH_PREFIX = "/UploadFile/";
    public static  $PRODUCT_ROOT= "#";
    public static  $HS256_KEY = "1y9eu20wfh9evyhoeasyhayhzfvewcjskfv";
    public static  $HS256 = "HS256";
    public static  $API_TYPE_OPENING = 1;
    public static  $API_TYPE_PERMISSON = 2;
    public static  $API_TYPE_NOT_EXITS = 3;
    public static  $API_STATUS_NOT = 1;
    public static  $API_STATUS_COMMON = 0;
}
