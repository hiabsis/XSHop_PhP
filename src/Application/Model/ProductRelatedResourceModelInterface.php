<?php

namespace Application\Model;

interface ProductRelatedResourceModelInterface
{
    /**
     * User: 无畏泰坦
     * Date: 2021.12.22 9:13
     * Describe
     * 批量保存
     * @param array $relatedResources  ProductRelatedResource array
     * @return bool
     */
    public function saveProductRelatedResourceBatch(array $relatedResources): bool;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.17 11:50
     * Describe 通过商品的Id 与资源类型获取 商品资源
     * @param int $productId
     * @param int $type
     * @return mixed
     */
    public function findProductResourceByProductIdAndType(int $productId, int $type):array;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.17 13:28
     * Describe 通过商品Id删除记录
     * @param int $productId
     * @return mixed
     */
    public function removeProductRelatedResourceByProductId(int $productId):bool;
    public function removeProductRelatedResourceByProductIdAndReSourceType(int $productId,int $type):bool;
    public function removeProductRelatedResourceById(int $id):bool;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.22 15:17
     * Describe
     * @param int $type 资源类型
     * @return array  数组元素为ProductRelatedResource
     */
    public function findProductResourceByType(int $type) :array;

    /**
     * User: 无畏泰坦
     * Date: 2021.12.28 17:42
     * Describe 查询商品资源关联信息
     * @param int $resourceType 资源类型
     *      轮播图 1 首页展示图  2 详情页展示图 3 ,如果是默认为-1,则查询所有类型的
     * @param int $relatedId  商品资源关联表ID
     * @param int $productId  商品ID
     * @param int $resourceId 系统资源表ID
     * @param array $select 查询字段
     *        在默认为空情况下,查询所有字段
     * @param int $page 分页查询范围 默认为0的情况查询所有数据
     * @param int $size 分页查询范围 默认为0的情况查询所有数据
     * @return array
     */
    public function listProductRelatedResource(int $resourceType = 0, int $relatedId=0, int $productId=0, int $resourceId=0, array $select = [], int $page =0, int $size = 0):array;
}
