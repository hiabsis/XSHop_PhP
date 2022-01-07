<?php
/**
 * User: 无畏泰坦
 * Date: 2022.01.05 17:38
 * Describe
 */

namespace Application\Service\System;
use Application\Domain\VO\TreeVO;
use Application\Exception\ServiceValidatorParamsException;
use Application\Model\MenuModelInterface;
use Application\Service\BaseService;
use Application\Service\MenuServiceInterface;

/**
 * Created on 2022.01.05 17:38
 * Created by 无畏泰坦
 * Describe
 */
class MenuService extends BaseService implements MenuServiceInterface
{
    /**
     * @var MenuModelInterface
     */
    private  $menuModel;
    public function __construct(MenuModelInterface $menuModel)
    {
        $this->menuModel = $menuModel;
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 17:43
     * Describe 分页查询菜单
     * @param int $page
     * @param int $size
     * @return TreeVO
     */
    public function listMenuByPage(int $page=-1,int $size = -1):TreeVO
    {
        $allMenu = $this->menuModel->findMenu();
        return $this->builderTreeResult($allMenu,['page'=>$page,'size'=>$size],label: 'name_zh');
    }
    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 17:43
     * Describe search
     * @param array $query
     * @return TreeVO
     */
    public function searchMenuByPage(array $query): TreeVO
    {
        $root = new TreeVO();
        $total = $this->menuModel->countMenu($query);
        $pageParams = $this->getPageParams($query,$total);
        $queryMenu = $this->menuModel->findMenu(queryCondition: $query,limit: $pageParams,isAnd: false);
        foreach ($queryMenu as &$menu){
            $
            $menu['label'] = $menu['name_zh'];
            $menu['children'] = null;
        }
        $root->total = $total;
        $root->children = $queryMenu;
        return  $root;
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe 更新数据
     * @param array $updateMenu
     * @param int $menuId
     * @return bool
     */
    public function updateMenu(array $updateMenu, int $menuId):bool
    {
        return $this->menuModel->updateMenuById(updateDate: $updateMenu,menuId: $menuId);
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe
     * @param array $ids
     * @return bool
     */
    public function removeMenu(array $ids ):bool
    {
        return $this->menuModel->removeMenuByIds(ids:$ids);
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe 数据保存
     * @param array $menu
     * @return int
     */
    public function saveMenu(array $menu):int
    {
        return $this->menuModel->saveMenu(saveData: $menu);
    }

}
