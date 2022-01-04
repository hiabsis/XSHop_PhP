<?php
/**
 * User: 无畏泰坦
 * Date: 2021.12.15 15:05
 * Describe
 */

namespace Application\Domain\Settings;

/**
 * Created on 2021.12.15 15:05
 * Created by 无畏泰坦
 * Describe
 */
interface ValidatorRuleInterface
{
    /**
     * User: 无畏泰坦
     * Date: 2021.12.15 15:06
     * Describe 获取校验规则
     * @param string $name
     * @return array
     */
    public function getValidatorRule(string $name):array;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.15 15:08
     * Describe 添加校验规则
     * @param array $rule
     * @return bool
     */
    public function add(array $rule):bool;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.15 15:10
     * Describe p 判断是否存在校验规则
     * @param string $name
     * @return mixed
     */
    public function has(string $name):bool;
}
