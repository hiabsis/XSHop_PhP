<?php
/**
 * User: 无畏泰坦
 * Date: 2022.01.05 17:38
 * Describe
 */

namespace Application\Service\System;
use Application\Domain\VO\TreeVO;
use Application\Exception\ServiceValidatorParamsException;
use Application\Model\ApiMenuModelInterface;
use Application\Model\ApiModelInterface;
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
    /**
     * @var ApiMenuModelInterface
     */
    private  $apiMenuModel;
    /**
     * @var ApiModelInterface
     */
    private  $apiModel;
    public function __construct(MenuModelInterface $menuModel,ApiMenuModelInterface $apiMenuModel ,ApiModelInterface $apiModel)

    {
        $this->apiMenuModel = $apiMenuModel;
        $this->apiModel = $apiModel;
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
        foreach ($allMenu as &$menu){
            $apiMenus = $this->apiMenuModel->findApiMenu(select: ['api_id'],queryCondition: ['menu_id'=>$menu['id']]);
            $menu['permission'] = [];
            if (!empty($apiMenus)){
                $apiIds = [];
                foreach ($apiMenus as $apiMenu){
                    $apiIds[] = $apiMenu['api_id'];
                }
                $menu['permission']  = $this->apiModel->findApi(select: ['name','permission','path'] ,queryCondition: ['id'=>$apiIds]);
            }
        }
        return $this->builderTreeResult($allMenu,$page,$size,label: 'name_zh');
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
