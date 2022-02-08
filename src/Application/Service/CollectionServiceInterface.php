<?php
/**
 * Collect: 无畏泰坦
 * Date: 2021.12.15 16:54
 * Describe
 */

namespace Application\Service;

use Application\Domain\System\Collect;

/**
 * Created on 2021.12.15 16:54
 * Created by 无畏泰坦
 * Describe 用户
 */
interface CollectionServiceInterface
{


    /**
     * Collect: 无畏泰坦
     * Date: 2022.01.05 17:43
     * Describe 分页查询菜单
     * @param array $query
     * @return array
     */
    public function listCollectByPage(array $query = []):array;


    /**
     * Collect: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe
     * @param int $collectId
     * @param int $userId
     * @return bool
     */
    public function removeCollect(int  $collectId,int $userId ):bool;

    /**
     * Collect: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe 数据保存
     * @param array $collect
     * @return int
     */
    public function saveCollect(array $collect):int;



}
