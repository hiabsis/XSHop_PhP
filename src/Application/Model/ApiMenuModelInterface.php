<?php
/**
 * @author     ：无畏泰坦
 * @date       ：Created in 2022.01.19 15:13
 * @description：
 * @modified By：
 * @version:     1.0
 */

namespace Application\Model;

interface ApiMenuModelInterface
{
    /**
     * Role: 无畏泰坦
     * Date: 2022.01.05 9:23
     * Describe 查找用户
     * @param array $select
     * @param array $queryCondition
     * @param array $limit
     * @param bool $isAnd
     * @return mixed
     */
    public function findApiMenu(array $select = [], array $queryCondition = [], array $limit = [], bool $isAnd = true):array;

}