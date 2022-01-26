<?php
/**
 * @author     ：无畏泰坦
 * @date       ：Created in 2022.01.21 21:34
 * @description：
 * @modified By：
 * @version:     1.0
 */

namespace Application\Service\System;

use Application\Model\ApiMenuModelInterface;
use Application\Model\ApiModelInterface;
use Application\Model\MenuModelInterface;
use Application\Service\ApiServiceInterface;
use Application\Service\BaseService;

class ApiService extends BaseService implements ApiServiceInterface
{
    /**
     * @var ApiModelInterface
     */
    private $apiModel;
    /**
     * @var ApiMenuModelInterface
     */
    private $apiMenuModel;
    /**
     * @var MenuModelInterface
     */
    private $menuModel;
    public function __construct(ApiModelInterface $apiModel,ApiMenuModelInterface $apiMenuModel,MenuModelInterface $menuModel)
    {
        $this->apiMenuModel = $apiMenuModel;
        $this->apiModel = $apiModel;
        $this->menuModel = $menuModel;
    }

    public function listApiByPage(array $query = []): array
    {
        $total = $this->apiModel->countApi($query);
        $pageParams = $this->getPageParams($query,$total);
        $data = $this->apiModel->findApi(queryCondition: $query,limit: $pageParams);
        foreach ($data as &$d){
            if ($d['status'] === 1){
                $d['status'] = true;
            }else{
                $d['status'] = false;
            }
            $apiMenus = $this->apiMenuModel->findApiMenu(select: ['menu_id'] ,queryCondition: ['api_id'=>$d['id']]);
            $d['menuIds'] = [];
            if (!empty($apiMenus)){
                $menuIds = [];
                foreach ($apiMenus as $apiMenu){
                    $menuIds[] = $apiMenu['menu_id'];
                }
                $menuIds = $this->menuModel->findMenu(select: ['id'],queryCondition: ['id'=>$menuIds]);
                $d['menuIds'] = [];
                foreach ($menuIds as $menuId){
                    $d['menuIds'][] = $menuId['id'];
                }
            }

        }
        return ['total' => $total,'data' => $data];
    }

    public function updateApi(array $updateApi, int $apiId,array $menuIds): bool
    {
        if (!empty($menuIds)){
            $this->apiMenuModel->removeApiMenu(deleteCondition: ['api_id'=>$apiId]);
            $apiMenus =  [];
            foreach ($menuIds as $menuId){
                $apiMenu = [];
                $apiMenu['api_id'] = $apiId;
                $apiMenu['menu_id'] = $menuId;
                $apiMenus[] = $apiMenu;
            }
            $this->apiMenuModel->saveApiMenu($apiMenus);
        }
        return  $this->apiModel->updateApiById($updateApi,$apiId);
    }

    public function removeApi(array $ids): bool
    {
        return  $this->apiModel->removeApiByIds($ids);
    }

    public function saveApi(array $api): int
    {

       return $this->apiModel->saveApi($api);
    }
    public function getApi(array $query): array
    {
        return $this->apiModel->getApi(queryCondition: $query);
    }

}