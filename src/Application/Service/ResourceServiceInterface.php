<?php

namespace Application\Service;



use Application\Domain\System\Resource;

interface ResourceServiceInterface
{
    /**
     * 文件记录保存
     */
    public function saveResource(Resource  $resource):array;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.31 13:07
     * Describe 获取文件的访问路径
     * @param array $queryCondition
     * @return array
     */
    public function getResourceInfo(array $queryCondition):array;

    public function removeResourceInfo(int $id,string $path):bool;
}
