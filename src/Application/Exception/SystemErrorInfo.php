<?php

namespace Application\Exception;

use PHPUnit\Exception;
use Predis\Command\Redis\SELECT;
use SebastianBergmann\GlobalState\RuntimeException;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;
use PDOException;

class SystemErrorInfo
{
    public const ERROR_CODE_PDO_EXCEPTION = 100001;
    public const ERROR_CODE_REDIS_EXCEPTION = 100002;
    public const ERROR_CODE_PDO_HTTP_NOT_FOUND_EXCEPTION = 200001;
    public const ERROR_CODE_HTTP_METHOD_NOT_ALLOWED_EXCEPTION = 200002;
    public const ERROR_CODE_HTTP_UNAUTHORIZED_EXCEPTION = 200003;
    public const ERROR_CODE_Http_FORBIDDEN_EXCEPTION = 200004;
    public const ERROR_CODE_HTTP_BAD_REQUEST_EXCEPTION = 200005;
    public const ERROR_CODE_CACHE_PERSISTENCE_EXCEPTION = 300001;
    public const ERROR_CODE_CACHE_PERSISTENCE_PARAMS_EXCEPTION = 300002;
    public const ERROR_CODE_CLAZZ_MAPPER_EXCEPTION = 300003;
    public const ERROR_CODE_MODEL_EXCEPTION = 300004;
    public const ERROR_CODE_MYSQL_INJECTION_EXCEPTION = 300005;
    public const ERROR_CODE_PARAMS_VALIDATOR_EXCEPTION = 300006;
    public const ERROR_CODE_REDIS_KEY_ARGUMENT_EXCEPTION = 300007;
    public const ERROR_CODE_SERVICE_EXCEPTION = 300008;
    public const ERROR_CODE_UPLOAD_EXCEPTION = 300009;
    public const ERROR_CODE_XSS_ATTACKING_EXCEPTION = 300010;
    public const ERROR_CODE_SYSTEM_ERROR = 100002;
    public const ERROR_CODE_DANGER_REQUEST = 100003;
    public const ERROR_CODE_REDIS_PARAMS_EXCEPTION = 300011;
    public const ERROR_CODE_MYSQL_SAVE_EXCEPTION = 300012;
    public const ERROR_CODE_MODEL_PARAMS_EXCEPTION = 300013;
    public const ERROR_CODE_VALIDATOR_RULES_NOT_FOUND_EXCEPTION = 300013;
    /**
     * @var array 异常的日志信息
     */
    protected static $ERROR_LOG_INFO = [
        ModelValidatorParamsException::class => [
            'code' => self::ERROR_CODE_MODEL_PARAMS_EXCEPTION
        ],
        ValidatorRulesNotFoundException::class =>[
            'code' => self::ERROR_CODE_VALIDATOR_RULES_NOT_FOUND_EXCEPTION,
        ],
        PDOException::class => [
            'code' => self::ERROR_CODE_PDO_EXCEPTION,
        ],
        \RedisException::class => [
            'code' => self::ERROR_CODE_REDIS_EXCEPTION
        ],
        HttpNotFoundException::class => [
            'code' => self::ERROR_CODE_PDO_HTTP_NOT_FOUND_EXCEPTION,
            'msg' => '资源未找到'
        ],
        HttpMethodNotAllowedException::class => [
            'code' => self::ERROR_CODE_HTTP_METHOD_NOT_ALLOWED_EXCEPTION,
            'msg' => '请求方法不允许'
        ],
        HttpUnauthorizedException::class => [
            'code' => self::ERROR_CODE_HTTP_UNAUTHORIZED_EXCEPTION,
            'msg' => '未认证'
        ],
        HttpForbiddenException::class => [
            'code' => self::ERROR_CODE_Http_FORBIDDEN_EXCEPTION,
            'msg' => '权限不足'
        ],
        HttpBadRequestException::class => [
            'code' => self::ERROR_CODE_HTTP_BAD_REQUEST_EXCEPTION,
            'msg' => '请求异常'
        ],
        CachePersistenceException::class => [
            'code' => self::ERROR_CODE_CACHE_PERSISTENCE_EXCEPTION,
            'msg' => '缓存的持久化异常',
        ],
        CachePersistenceParamsException::class => [
            'code' => self::ERROR_CODE_CACHE_PERSISTENCE_PARAMS_EXCEPTION,
            'msg' => '缓存持久化参数异常',
        ],
        ClazzMapperException::class => [
            'code' => self::ERROR_CODE_CLAZZ_MAPPER_EXCEPTION,
            'msg' => '类属性映射异常',
        ],

        MysqlInjectionException::class => [
            'code' => self::ERROR_CODE_MYSQL_INJECTION_EXCEPTION,
            'msg' => '参数有MSQL注入',
        ],
        ValidatorParamsException::class => [
            'code' => self::ERROR_CODE_PARAMS_VALIDATOR_EXCEPTION,
            'msg' => '参数校验失败',
        ],
        RedisKeyArgumentException::class =>[
            'code' => self::ERROR_CODE_REDIS_KEY_ARGUMENT_EXCEPTION,
            'msg' => 'redis键异常',
        ],

        UploadException::class => [
            'code' => self::ERROR_CODE_UPLOAD_EXCEPTION,
            'msg' => '文件上次失败'
        ],
        XSSAttackingException::class => [
            'code' => self::ERROR_CODE_XSS_ATTACKING_EXCEPTION,
            'msg' => '请求含有XSS攻击'
        ],
        ServiceException::class => [
            'code' => self::ERROR_CODE_SERVICE_EXCEPTION,
        ],
        ModelException::class => [
            'code' => self::ERROR_CODE_MODEL_EXCEPTION,
        ],
        RedisHelperParamsException::class => [
            'code' => self::ERROR_CODE_REDIS_PARAMS_EXCEPTION
        ],
        MysqlSaveException::class => [
            'code' => self::ERROR_CODE_MYSQL_SAVE_EXCEPTION
        ]


    ];
    /**
     * @var array 异常的提示信息 —— 返回给前端展示使用
     */
    protected static $ERROR_INFO = [
        PDOException::class => [
            'code' => self::ERROR_CODE_SYSTEM_ERROR,
            'msg' => '系统异常',
        ],
        ValidatorRulesNotFoundException::class => [
            'code' => self::ERROR_CODE_SYSTEM_ERROR,
            'msg' => '系统异常',
        ],
        ModelValidatorParamsException::class => [
            'code' => self::ERROR_CODE_SYSTEM_ERROR,
            'msg' => '系统异常',
        ],
        \RedisException::class => [
            'code' => self::ERROR_CODE_SYSTEM_ERROR,
            'msg' => '系统异常',
        ],
        ServiceException::class => [
            'code' => self::ERROR_CODE_SYSTEM_ERROR,
            'msg' => '系统异常',
        ],
        ModelException::class => [
            'code' => self::ERROR_CODE_MODEL_EXCEPTION,
            'msg' => '系统异常',
        ],
        RedisHelperParamsException::class => [
            'code' => self::ERROR_CODE_MODEL_EXCEPTION,
            'msg' => '系统异常',
        ],
        Exception::class => [
            'code' => self::ERROR_CODE_SYSTEM_ERROR,
            'msg' => '系统异常',
        ],
        MysqlSaveException::class => [
            'code' => self::ERROR_CODE_MODEL_EXCEPTION,
            'msg' => '系统异常',
        ],

        HttpNotFoundException::class => [
            'code' => self::ERROR_CODE_PDO_HTTP_NOT_FOUND_EXCEPTION,
            'msg' => '资源未找到'
        ],

        HttpMethodNotAllowedException::class => [
            'code' => self::ERROR_CODE_HTTP_METHOD_NOT_ALLOWED_EXCEPTION,
            'msg' => '请求方法不允许'
        ],
        HttpUnauthorizedException::class => [
            'code' => self::ERROR_CODE_HTTP_UNAUTHORIZED_EXCEPTION,
            'msg' => '未认证'
        ],
        HttpForbiddenException::class => [
            'code' => self::ERROR_CODE_Http_FORBIDDEN_EXCEPTION,
            'msg' => '权限不足'
        ],
        HttpBadRequestException::class => [
            'code' => self::ERROR_CODE_HTTP_BAD_REQUEST_EXCEPTION,
            'msg' => '请求异常'
        ],
        CachePersistenceException::class => [
            'code' => self::ERROR_CODE_SYSTEM_ERROR,
            'msg' => '系统异常',
        ],
        CachePersistenceParamsException::class => [
            'code' => self::ERROR_CODE_SYSTEM_ERROR,
            'msg' => '系统异常',
        ],
        ClazzMapperException::class => [
            'code' => self::ERROR_CODE_SYSTEM_ERROR,
            'msg' => '系统异常',
        ],

        MysqlInjectionException::class => [
            'code' => self::ERROR_CODE_DANGER_REQUEST,
            'msg' => '不安全请求,拒绝处理',
        ],
        ValidatorParamsException::class => [
            'code' => self::ERROR_CODE_PARAMS_VALIDATOR_EXCEPTION,
            'msg' => '参数校验失败',
        ],
        RedisKeyArgumentException::class =>[
            'code' => self::ERROR_CODE_SYSTEM_ERROR,
            'msg' => '系统异常',
        ],

        UploadException::class => [
            'code' => self::ERROR_CODE_UPLOAD_EXCEPTION,
            'msg' => '文件上次失败'
        ],
        XSSAttackingException::class => [
            'code' => self::ERROR_CODE_DANGER_REQUEST,
            'msg' => '不安全请求,拒绝处理'
        ],


    ];

    /**
     * User: 无畏泰坦
     * Date: 2021.12.21 10:32
     * Describe 通过异常实例获取预定义的异常信息
     * @param  $exception
     * @return array
     */
    public static function getErrorInfo( $exception):array
    {
        $clazz = new \ReflectionObject($exception);
        $error =  self::$ERROR_LOG_INFO[$clazz->getName()];
        if (empty($error)){
            $error = [
                'code' => 100000,
                'msg' => '系统异常'
            ];
        }
        return self::$ERROR_INFO[$clazz->getName()];
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.21 10:32
     * Describe 通过异常实例获取预定义的异常日志信息
     * @param  $exception
     * @return array
     */
    public static function getErrorLogInfo( $exception):array
    {
        $clazz = new \ReflectionObject($exception);
        $error =  self::$ERROR_LOG_INFO[$clazz->getName()];
        $error['position'] = $exception->getFile() . ' '. $exception->getLine();
        $error['msg'] = $exception->getMessage();
        $error['trance']  = $exception->getTrace();
        return $error;
    }

}
