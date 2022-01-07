<?php
/**
 * User: 无畏泰坦
 * Date: 2022.01.05 17:30
 * Describe
 */

namespace Application\Service;

use Application\Domain\VO\TreeVO;

/**
 * Created on 2022.01.05 17:30
 * Created by Administrator
 * Describe
 */
interface MenuServiceInterface
{

    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 17:43
     * Describe 分页查询菜单
     * @param int $page
     * @param int $size
     * @return TreeVO
     */
    public function listMenuByPage(int $page=-1,int $size = -1):TreeVO;

    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 17:43
     * Describe search
     * @param array $query
     * @return TreeVO
     */
    public function searchMenuByPage(array $query):TreeVO;
    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe 更新数据
     * @param array $updateMenu
     * @param int $menuId
     * @return bool
     */
    public function updateMenu(array $updateMenu, int $menuId):bool;

    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe
     * @param array $ids
     * @return bool
     */
    public function removeMenu(array $ids ):bool;

    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe 数据保存
     * @param array $menu
     * @return int
     */
    public function saveMenu(array $menu):int;



}
