<?php
namespace Application\Constant;


use Application\Exception\CachePersistenceException;
use Application\Exception\CachePersistenceParamsException;
use Application\Exception\ClazzMapperException;
use Application\Exception\ModelException;
use Application\Exception\ModelValidatorParamsException;
use Application\Exception\MysqlInjectionException;
use Application\Exception\MysqlSaveException;
use Application\Exception\RedisHelperParamsException;
use Application\Exception\RedisKeyArgumentException;
use Application\Exception\ServiceException;
use Application\Exception\UploadException;
use Application\Exception\ValidatorParamsException;
use Application\Exception\ValidatorRulesNotFoundException;
use Application\Exception\XSSAttackingException;
use PHPUnit\Exception;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;

/**
 * Created on 2021.12.28 15:48
 * Created by 无畏泰坦
 * Describe 系统异常信息
 */
class ErrorEnum
{
    /*
     * 错误信息
     *
     */
    public static $ERROR_400 = ["code"=>"400", "message"=>"400 Bad Request"];
    public static $ERROR_401 = ["code"=>"401","message"=> "该请求需要有效的用户身份验证。"];
    public static $ERROR_403 = ["code"=>"403","message"=> "无权限"];
    public static $ERROR_404 = ["code"=>"404","message"=> "目标资源在源服务器上未找到"];
    public static $ERROR_410 = ["code"=>"404","message"=> "目标资源在源服务器上不再可用"];
    public static $ERROR_500=["code"=>500,"message"=> "阻止服务器完成请求"];
    public static $ERROR_501=["code"=>501, "message"=>"服务器不支持完成请求所需的功能"];
    public static $ERROR_502=["code"=>502, "message"=>"权限不足"];
    public static $ERROR_10008=["code"=>"10008", "message"=>"角色删除失败,尚有用户属于此角色"];
    public static $ERROR_10009=["code"=>"10009", "message"=>"账户已存在"];
    public static $ERROR_10010=["code"=>"10010", "message"=>"账号/密码错误"];
    public static $ERROR_20011=["code"=>"20011", "message"=>"登陆已过期,请重新登陆"];
    public static $ERROR_20000=["code"=>"20000","message"=> "登入账号错误"];
    public static $ERROR_20001=["code"=>"20001","message"=> "登入密码错误"];
   public static  $ERROR_90003=["code"=>"90003", "message"=>"缺少必填参数"];
   public static  $ERROR_30001=["code"=>"30001","message"=> "文件不存在"];
}
