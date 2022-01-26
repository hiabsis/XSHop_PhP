<?php
/**
 * @author     ：无畏泰坦
 * @date       ：Created in 2022.01.21 21:32
 * @description：
 * @modified By：
 * @version:     1.0
 */

namespace Application\Service;

interface ApiServiceInterface
{

    /**
     * Role: 无畏泰坦
     * Date: 2022.01.05 17:43
     * Describe 分页查询菜单
     * @param array $query
     * @return array
     */
    public function listApiByPage(array $query = []):array;


    /**
     * Role: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe 更新数据
     * @param array $updateApi
     * @param int $RoleId
     * @return bool
     */
    public function updateApi(array $updateApi, int $apiId,array $menuIds):bool;

    /**
     * Role: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe
     * @param array $ids
     * @return bool
     */
    public function removeApi(array $ids ):bool;

    /**
     * Role: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe 数据保存
     * @param array $api
     * @return int
     */
    public function saveApi(array $api):int;

    public function getApi(array $query):array;


}