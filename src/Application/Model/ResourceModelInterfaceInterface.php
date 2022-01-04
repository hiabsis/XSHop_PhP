<?php

namespace Application\Model;



use Application\Domain\System\Resource;

interface ResourceModelInterfaceInterface extends BaseModelInterface
{

    /**
     * User: 无畏泰坦
     * Date: 2021.12.22 9:32
     * Describe 批量删除
     * @param array $ids
     * @return bool
     */
    public function removeResourceByIds(array $ids):bool;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.22 9:32
     * Describe 更新
     * @param Resource $resource
     * @return bool
     */
    public function updateResource(Resource $resource):bool;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.22 9:30
     * Describe 资源保存
     * @param Resource $resource
     * @return bool
     */
    public function saveResource(Resource $resource) :int;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.31 13:29
     * Describe 获取资源商品的图片资源
     * @param int $productId
     * @param int $type
     * @return array
     */
    public function findProductResourceByProductIdAndType(int $productId,int $type):array;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.22 15:34
     * Describe 通过Id获取单条记录
     * @param int $id
     * @return Resource
     */
    public function getResourceById(int $id): Resource;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.29 11:16
     * Describe 查询系统资源 在查询条件为默认值的情况下不做为查询条件
     * @param int $resourceId 资源表的主键
     * @param int $userId 用户Id
     * @param string $resourceName 资源名称
     * @param array $resourceMine 资源类型
     * @param array $select 查询字段
     *        在默认为空情况下,查询所有字段
     * @param int $page 分页查询范围 默认为0的情况查询所有数据
     * @param int $size 分页查询范围 默认为0的情况查询所有数据
     * @return array
     */
    public function listResource(int $resourceId = 0 ,
                                 int $userId=0,
                                 string $resourceName= '',
                                 array $resourceMine = [],
                                 array $select = [],
                                 int $page = 0,
                                 int $size = 0):array;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.29 13:06
     * Describe 通过资源表的主键数组批量获取数据
     * @param array $resourceIds
     * @param array $select 查询字段
     *        在默认为空情况下,查询所有字段
     * @return array
     */
    public function getResourceByIds(array $resourceIds = [], array $select = []) :array;

}
