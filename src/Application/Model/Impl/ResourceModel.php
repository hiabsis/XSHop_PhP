<?php

namespace Application\Model\Impl;


use Application\Domain\System\Resource;
use Application\Exception\ModelException;
use Application\Exception\ModelValidatorParamsException;
use Application\Model\ResourceModelInterfaceInterface;
use Medoo\Medoo;
use PDO;

class ResourceModel extends BaseModel implements ResourceModelInterfaceInterface
{

    public function __construct(PDO $conn, Medoo $medoo)
    {
        parent::__construct($conn, $medoo);
        $this->tableName = 'sys_resource';
    }


    public function removeResourceByIds(array $ids): bool
    {
        return $this->deleteIds($ids);
    }

    public function updateResource(Resource $resource): bool
    {
        return $this->update($resource);
    }

    public function saveResource(Resource $resource): int
    {
        return $this->insert($resource);
    }

    public function findProductResourceByProductIdAndType(int $productId, int $type): array
    {
        if (empty($productId) || empty($type)) {
            throw new ModelValidatorParamsException('$productId type 为空');
        }
        $sql = "select * from sys_resource where id  in (select resource_id from shop_product_related_resource where product_id = ? and resource_type = ?)";
        $query = $this->conn->prepare($sql);
        $query->execute([$productId, $type]);
        $res = [];
        while ($result = $query->fetch(\PDO::FETCH_ASSOC)) {
            $temp = new Resource();
            foreach ($result as $k => $v) {
                $tansferKey = $this->toCamelCase($k);
                $temp->$tansferKey = $v;
            }
            $res[] = $temp;
        }

        return $res;
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.22 15:34
     * Describe 通过Id获取单条记录
     * @param int $id
     * @return Resource
     */
    public function getResourceById(int $id = 0): Resource
    {
        $arr = $this->findByCondition(where: [' id = ? '], binds: [$id], clazz: Resource::class);
        if (empty($arr)) {
            return new Resource();
        }
        return $arr[0];
    }

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
    public function listResource(int $resourceId = 0, int $userId = 0, string $resourceName = '', array $resourceMine = [], array $select = [], int $page = 0, int $size = 0): array
    {

        $where = []; // 数据查询条件字段
        $binds = []; // 查询所绑定的数据
        if ($userId !== 0) {
            $where[] = ' user_id = ? ';
            $binds[] = $userId;
        }
        if ($resourceId !== 0) {
            $where[] = ' id = ? ';
            $binds[] = $resourceId;
        }
        if ($resourceName !== '') {
            $where[] = ' name = ? ';
            $binds[] = "%%$resourceId%%";
        }
        if (!empty($resourceMine)) {

            $where[] = ' mine in  ' . $this->generatePlaceHolder(count($resourceMine), count($resourceMine));
            $binds[] = array_merge($binds, $resourceMine);
        }
        return $this->selectByCondition(where: $where, binds: $binds, select: $select, page: $page, size: $size);
    }

    public function getResourceByIds(array $resourceIds = [], array $select = [],): array
    {
        $where = []; // 数据查询条件字段
        $binds = $resourceIds; // 查询所绑定的数据

        if (empty($resourceIds)) {
            return [];
        }
        foreach ($resourceIds as $id) {
            if (!is_int($id)) {
                throw new ModelValidatorParamsException("参数要求int数组,但是获取是" . gettype($id));
            }
        }
        $where[] = ' id in  ' . $this->generatePlaceHolder(count($resourceIds), count($resourceIds));
        return $this->selectByCondition(where: $where, binds: $binds, select: $select);
    }

}
