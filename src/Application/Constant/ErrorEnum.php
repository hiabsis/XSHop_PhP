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
    public static $ERROR_400 = ["400", "400 Bad Request"];
    public static $ERROR_401 = ["401", "该请求需要有效的用户身份验证。"];
    public static $ERROR_403 = ["403", "无权限"];
    public static $ERROR_404 = ["404", "目标资源在源服务器上未找到"];
    public static $ERROR_410 = ["404", "目标资源在源服务器上不再可用"];
    public static $ERROR_500=["500", "阻止服务器完成请求"];
    public static $ERROR_501=["501", "服务器不支持完成请求所需的功能"];
    public static $ERROR_502=["502", "权限不足"];
    public static $ERROR_10008=["10008", "角色删除失败,尚有用户属于此角色"];
    public static $ERROR_10009=["10009", "账户已存在"];
    public static $ERROR_10010=["10010", "账号/密码错误"];
    public static $ERROR_20011=["20011", "登陆已过期,请重新登陆"];
    public static $ERROR_20000=["20000", "登入账号错误"];
    public static $ERROR_20001=["20001", "登入密码错误"];
   public static  $ERROR_90003=["90003", "缺少必填参数"];
}
