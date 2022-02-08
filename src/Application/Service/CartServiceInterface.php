<?php
/**
 * Collect: 无畏泰坦
 * Date: 2021.12.15 16:54
 * Describe
 */

namespace Application\Service;

use Application\Domain\System\Collect;

/**
 * Created on 2021.12.15 16:54
 * Created by 无畏泰坦
 * Describe 用户
 */
interface CartServiceInterface
{


    /**
     * Collect: 无畏泰坦
     * Date: 2022.01.05 17:43
     * Describe 分页查询菜单
     * @param array $query
     * @return array
     */
    public function listCartByPage(array $query = []):array;


    /**
     * Collect: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe
     * @param int $cartId
     * @param int $userId
     * @return bool
     */
    public function removeCart(int  $cartId,int $userId ):bool;

    /**
     * Collect: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe 数据保存
     * @param array $cart
     * @return int
     */
    public function saveCart(array $cart):array;


    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 14:30
     * Describe
     * @param array $cart
     * @param int $cartId
     * @param int $userId
     * @return bool
     */
    public function updateCart(array $cart, int $cartId,int $userId ):int;
}
