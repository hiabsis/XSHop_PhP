<?php

namespace  Application\Helper;
use Application\Domain\Product\Category;
use Application\Domain\Product\Product;
use Application\Domain\Product\ProductInfo;
use Application\Domain\System\Resource;
use Application\Exception\ValidatorParamsException;
use Application\Exception\MysqlInjectionException;
use Application\Exception\XSSAttackingException;
use   \FangStarNet\PHPValidator\Validator;
/**
 * 参数校验辅助类 使用 fangstar/php-validator
 */

class ValidatorHelper
{
    public static $HTTP_XSS_RULES = [
        '\.\./', //禁用包含 ../ 的参数
        '\<\?', //禁止php脚本出现
        '(?:etc\/\W*passwd)', //防止窥探linux用户信息
        '(?:define|eval|file_get_contents|include|require|require_once|shell_exec|phpinfo|system|passthru|preg_\w+|execute|echo|print|print_r|var_dump|(fp)open|alert|showmodaldialog)\(', //禁用webshell相关某些函数
        '(gopher|doc|php|glob|file|phar|zlib|ftp|ldap|dict|ogg|data)\:\/', //防止一些协议攻击
        '\$_(GET|post|cookie|files|session|env|phplib|GLOBALS|SERVER)\[', //禁用一些内置变量,建议自行修改
        '\<(iframe|script|body|img|layer|div|meta|style|base|object|input)', //防止xss标签植入
        '(onmouseover|onerror|onload|onclick)\=', //防止xss事件植入
        '\|\|.*(?:ls|pwd|whoami|ll|ifconfog|ipconfig|&&|chmod|cd|mkdir|rmdir|cp|mv)', //防止执行shell
    ];
    public static $SQL_INJECTION_RULES  = [

        '\s*or\s+.*=.*', //匹配' or 1=1 ,防止sql注入
        'select([\s\S]*?)(from|limit)', //防止sql注入
        '(?:(union([\s\S]*?)select))', //防止sql注入
        'having|updatexml|extractvalue', //防止sql注入
        'sleep\((\s*)(\d*)(\s*)\)', //防止sql盲注
        'benchmark\((.*)\,(.*)\)', //防止sql盲注
        'base64_decode\(', //防止sql变种注入
        '(?:from\W+information_schema\W)', //防止sql注入
        '(?:(?:current_)user|database|schema|connection_id)\s*\(', //防止sql注入
        'into(\s+)+(?:dump|out)file\s*', //禁用mysql导出函数
        'group\s+by.+\(', //防止sql注入

        '\s*and\s+.*=.*' //匹配 and 1=1
    ];
    /**
     * 类属性规则校验
     * @var array
     */
    public static $classRules = [
        ProductInfo::class => [
            'id' => 'integer|length_max:11',
            'name' => 'string|length_max:32',
            'value' => 'string|length_max:64',
            'type' =>'integer|in:1,2,3,4',
        ],
        Category::class => [
            'id' => 'integer|length_max:11',
            'name' => 'string|length_max:32',
            'parentId' => 'integer|length_max:11',
            'status' => 'integer|in:1,2'
        ],
        Product::class => [
            'id' => 'integer|length_max:11',
            'userId' => 'integer|length_max:11',
            'productId' => 'integer|length_max:11',
            'productStatus' => 'integer|length_max:2|in:1,2,3',
            'productDesc' => 'string|length_max:255',
            'productNumber' => 'integer|length_max:11',
            'productPrice' => 'float',
            'productDetail' => 'string',
            'productName' => 'string|length_max:32',
        ],
        Resource::class => [
            'id' => 'integer|length_max:11',
            'userId' =>'integer|length_max:11',
            'fileSize' =>'integer|length_max:11',
            'fileName' =>'string|length_max:255',
            'fileType' =>'integer|length_max:2|in:1,2,3',
            'url' =>'string|length_max:255',
            'createTime' =>'string|length_max:255',
            'fileLocalPath' => 'string|length_max:255'
        ],


    ];
    public static function validator($data,$rules){

        if(empty($rules)){
            return;
        }
        Validator::make($data,$rules);
        if (Validator::has_fails()) {
            throw new ValidatorParamsException(Validator::error_msg());
        }
    }


    /**
     * 拦截sql注入
     * @param string $data
     * @return bool
     */
    public static function checkSqlInjection(string $data){
        foreach (self::$SQL_INJECTION_RULES as $rule) {
            if (preg_match('^' . $rule . '^i', $data)) {
               throw new MysqlInjectionException();
            }
        }
        return true;
    }

    public static function hasXSSAttacking(string $data){

        foreach (self::$HTTP_XSS_RULES as $rule) {
            if (preg_match('^' . $rule . '^i', $data)) {
                throw new XSSAttackingException();
            }
        }
        return true;
    }

    public static function getRules($key){
        return empty(self::$classRules[$key])? [] : self::$classRules[$key];
    }
}
