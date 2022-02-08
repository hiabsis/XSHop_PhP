<?php
/**
 * @author     ：无畏泰坦
 * @date       ：Created in 2022.02.08 9:51
 * @description：
 * @modified By：
 * @version:     1.0
 */

namespace Application\Model;

interface OrderModel
{
    public function findOrder(array $select = [], array $queryCondition = [], array $limit = [], bool $isAnd = true):array;
    public function countOrder(array $queryCondition = []);
    public function getOrder(array $select = [], array $queryCondition = []);
    public function updateOrderById(array $updateDate = [], int $orderId = 0): bool;
    public function removeOrderByIds(array $ids = []): bool;
    public function saveOrder(array $saveData): bool;
}