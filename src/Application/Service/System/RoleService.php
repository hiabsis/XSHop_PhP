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
    private $RoleModel;
    public function __construct(RoleModelInterface $RoleModel)
    {
        $this->RoleModel = $RoleModel;
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
        return ['total' => $total,'data' => $data];
    }


    /**
     * Role: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe 更新数据
     * @param array $updateRole
     * @param int $RoleId
     * @return bool
     */
    public function updateRole(array $updateRole, int $RoleId): bool
    {
       return  $this->RoleModel->updateRoleById($updateRole,$RoleId);
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
       return $this->RoleModel->removeRole($ids);
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
