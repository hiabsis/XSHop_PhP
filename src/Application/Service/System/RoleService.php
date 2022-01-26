<?php
/**
 * Role: 无畏泰坦
 * Date: 2021.12.15 16:55
 * Describe
 */

namespace Application\Service\System;

use Application\Domain\System\Role;
use Application\Exception\ModelException;
use Application\Exception\ServiceException;
use Application\Model\RoleMenuModelInterface;
use Application\Model\RoleModelInterface;
use Application\Service\BaseService;
use Application\Service\RoleServiceInterface;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Created on 2021.12.15 16:55
 * Created by 无畏泰坦
 * Describe
 */
class RoleService extends  BaseService implements RoleServiceInterface
{
    /**
     * @var RoleModelInterface
     */
    private $RoleModel;
    /**
     * @var RoleMenuModelInterface
     */
    private $roleMenuModel;
    public function __construct(RoleModelInterface $RoleModel,RoleMenuModelInterface $roleMenuModel)
    {
        $this->RoleModel = $RoleModel;
        $this->roleMenuModel = $roleMenuModel;
    }



    /**
     * Role: 无畏泰坦
     * Date: 2022.01.05 17:43
     * Describe 分页查询菜单
     * @param array $query
     * @return array
     */
    #[ArrayShape(['total' => "int", 'data' => "array"])] public function listRoleByPage(array $query = []): array
    {
        $total = $this->RoleModel->countRole($query);
        $pageParams = $this->getPageParams($query,$total);
        $data = $this->RoleModel->findRole(queryCondition: $query,limit: $pageParams);
        foreach ($data as &$d){
            if ($d['enabled'] === 1){
                $d['enabled'] = true;
            }else{
                $d['enabled'] = false;
            }
            $d['menuIds'] = [];
            $roleMenus = $this->roleMenuModel->findRoleMenu(select: ['menu_id'],queryCondition: ['role_id'=>$d['id']]);
            if (!empty($roleMenus)){
                foreach ($roleMenus as $roleMenu){
                    $d['menuIds'][] = $roleMenu['menu_id'];
                }

            }

        }
        return ['total' => $total,'data' => $data];
    }


    /**
     * Role: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe 更新数据
     * @param array $updateRole
     * @param int $roleId
     * @return bool
     */
    public function updateRole(array $updateRole, int $roleId, array $menuIds): bool
    {
        $this->roleMenuModel->removeRoleMenu(deleteCondition: ["role_id"=>$roleId]);
        if (!empty($menuIds)){

            $roleMenus = [];
            foreach ($menuIds as $menuId){
                $roleMenu = [];
                $roleMenu['role_id'] = $roleId;
                $roleMenu['menu_id'] = $menuId;
                $roleMenus[] = $roleMenu;
            }
            $this->roleMenuModel->saveRoleMenu($roleMenus);
        }
       return  $this->RoleModel->updateRoleById($updateRole,$roleId);
    }
    /**
     * Role: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe
     * @param array $ids
     * @return bool
     */
    public function removeRole(array $ids): bool
    {
       return $this->RoleModel->removeRoleByIds($ids);
    }
    /**
     * Role: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe 数据保存
     * @param array $Role
     * @return int
     */
    public function saveRole(array $Role): int
    {
       return $this->RoleModel->saveRole($Role);
    }
    /**
     * Role: 无畏泰坦
     * Date: 2022.01.07 11:24
     * Describe 获取用户
     * @param string $name
     * @param string $password
     * @return array
     */
    public function getRole(string $name, string $password):array
    {
        return  $this->RoleModel->getRole(['name'=>$name,'password'=>$password]);
    }



}
