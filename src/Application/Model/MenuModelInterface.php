<?php


namespace Application\Model;

/**
 * Created on 2022.01.05 16:34
 * Created by istrator
 * Describe
 */
interface MenuModelInterface
{
    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 16:43
     * Describe
     * @param array $select
     * @param array $queryCondition
     * @param array $limit
     * @param bool $isAnd
     * @return array
     */
    public function findMenu(array $select = [],array $queryCondition = [],array $limit = [],bool $isAnd = true): array;

    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 17:01
     * Describe 批量删除
     * @param array $ids
     * @return bool
     */
    public function removeMenuByIds(array $ids = []): bool;
    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 17:01
     * Describe 更新菜单
     * @param array $updateDate
     * @param int $menuId
     * @return bool
     */
    public function updateMenuById(array $updateDate = [], int $menuId = 0): bool;

    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 17:35
     * Describe 保存菜单
     * @param $saveData
     * @return int
     */
    public function saveMenu($saveData): int;
    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 17:36
     * Describe 统计数据
     * @param array $queryCondition
     * @return int|null
     */
    public function countMenu(array $queryCondition);

    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 17:28
     * Describe 是否存在
     * @param string $name
     * @return bool
     */
    public function hasMenu(string $name = ''):bool;



}
