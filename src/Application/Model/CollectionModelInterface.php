<?php
/**
 * @author     ：无畏泰坦
 * @date       ：Created in 2022.01.27 10:00
 * @description：商品收藏
 * @modified By：
 * @version:     1.0
 */

namespace Application\Model;

interface CollectionModelInterface
{


    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.27 10:12
     * @description：${description}
     * @modified By：
     * @version:     1.0
     */
    public function findCollect(array $select = [], array $queryCondition = [], array $limit = [], bool $isAnd = true): array;


    /**
     * Collect: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe 更新数据
     * @param array $updateCollect
     * @param int $CollectId
     * @param array $menuIds
     * @return bool
     */
    public function updateCollect(array $updateCollect, int $CollectId, array $menuIds):bool;

    /**
     * Collect: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe
     * @param array $ids
     * @return bool
     */
    public function removeCollect(array $ids ):bool;

    /**
     * Collect: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe 数据保存
     * @param array $collect
     * @return int
     */
    public function saveCollect(array $collect):int;

    /**
     * Collect: 无畏泰坦
     * Date: 2022.01.07 11:24
     * Describe
     * @param array $query
     * @return array
     */
    public function getCollect(array $select =[],array $query = []) : array;

    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.27 10:03
     * @description：${description}
     * @modified By：
     * @param array $query
     * @version:     1.0
     */
    public function countCollect(array $query = []): int;
}
