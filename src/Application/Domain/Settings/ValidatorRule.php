<?php
/**
 * User: 无畏泰坦
 * Date: 2021.12.15 15:07
 * Describe
 */

namespace Application\Domain\Settings;

use Application\Controller\System\LoginController;
use Application\Exception\ValidatorRulesNotFoundException;

/**
 * Created on 2021.12.15 15:07
 * Created by 无畏泰坦
 * Describe
 */
class ValidatorRule implements ValidatorRuleInterface
{
    private $rules ;
    public function __construct(array  $rules)
    {
        $this->rules = $rules;
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.15 15:25
     * Describe  获取检验规则
     * @param string $name
     * @return array
     */
    public function getValidatorRule(string $name): array
    {
       if ($this->has($name)){
           return $this->rules[$name];
       }else{
           throw new ValidatorRulesNotFoundException("校验规则未找到 $name");
       }

    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.15 15:31
     * Describe 添加校验规则
     * @param array $rule
     * @return bool
     */
    public function add(array $rule): bool
    {
        $this->rules = array_merge($this->rules,$rule);
        return true;
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.15 15:31
     * Describe 判断是否存在校验规则
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return array_key_exists($name,$this->rules);
    }
}
