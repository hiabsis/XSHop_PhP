<?php
/**
 * @author     ：无畏泰坦
 * @date       ：Created in 2022.01.27 10:00
 * @description：商品收藏
 * @modified By：
 * @version:     1.0
 */

namespace Application\Model;

interface CartModelInterface
{


    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.27 10:12
     * @description：${description}
     * @modified By：
     * @version:     1.0
     */
    public function findCart(array $select = [], array $queryCondition = [], array $limit = [], bool $isAnd = true): array;


    /**
     * Collect: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe 更新数据
     * @param array $update
     * @param int $cartId
     * @return bool
     */
    public function updateCart(array $update = [], int $cartId=0):bool;

    /**
     * Collect: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe
     * @param array $ids
     * @return bool
     */
    public function removeCart(array $ids ):bool;

    /**
     * Collect: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe 数据保存
     * @param array $collect
     * @return int
     */
    public function saveCart(array $collect):int;

    /**
     * Collect: 无畏泰坦
     * Date: 2022.01.07 11:24
     * Describe
     * @param array $query
     * @return array
     */
    public function getCart(array $select =[],array $query = []) : array;

    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.27 10:03
     * @description：${description}
     * @modified By：
     * @param array $query
     * @version:     1.0
     */
    public function countCart(array $query = []): int;
}
