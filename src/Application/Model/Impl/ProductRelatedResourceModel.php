<?php

namespace Application\Model\Impl;

use Application\Domain\Product\Product;
use Application\Domain\Product\ProductInfo;
use Application\Domain\Product\ProductRelatedResource;
use Application\Domain\System\Resource;
use Application\Exception\ModelException;
use Application\Exception\ModelValidatorParamsException;
use Application\Model\ProductInfoModelInterFace;
use Application\Model\ProductRelatedResourceModelInterface;
use Medoo\Medoo;
use PDO;

class ProductRelatedResourceModel extends BaseModel implements ProductRelatedResourceModelInterface
{

   public function __construct(PDO $conn, Medoo $medoo)
   {
       parent::__construct($conn, $medoo);
       $this->tableName = 'shop_product_related_resource';
   }


    public function saveProductRelatedResourceBatch(array $relatedResources):bool
    {
        foreach ($relatedResources as $item){
            if (! $item instanceof ProductRelatedResource){
                throw new ModelException(' params $productInfo require ' .ProductRelatedResource::class . ' but get ' . var_dump($productInfo,true)  ,ModelException::ERROR_PARAMS);
            }
        }
        return $this->insertBatch($relatedResources);
    }

    public function findProductResourceByProductIdAndType(int $productId, int $type):array
    {
        if (empty($productId) || empty($type)) {
            throw new ModelValidatorParamsException();
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
     * Date: 2021.12.17 13:28
     * Describe 通过商品Id删除记录
     * @param int $productId
     * @return mixed
     */
    public function removeProductRelatedResourceByProductId(int $productId): bool
    {
        return $this->deleteByCondition([
            'where' => [' product_id = ? '],
            'binds' => [$productId]
        ]);
    }
    public function removeProductRelatedResourceByProductIdAndReSourceType(int $productId,int $type): bool
    {
        return $this->deleteByCondition([
            'where' => [' product_id = ? ','resource_type = ?'],
            'binds' => [$productId,$type]
        ]);
    }

    public function removeProductRelatedResourceById(int $id): bool
    {
        return $this->deleteByCondition([
            'where' => [' id = ? '],
            'binds' => [$id]
        ]);
    }

    public function findProductResourceByType(int $type):array
    {
        if (empty($type)){
            throw new ModelValidatorParamsException("查找商品的关联信息e type 为空");
        }
        if ($type < 0){
            throw new ModelValidatorParamsException("查找商品的关联信息 type 为非负数");
        }
        return $this->findByCondition([
            'where' => [' resource_type = ? '],
            'binds' => [$type],
            'clazz' => ProductRelatedResource::class
        ]);
    }

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
     * @param int $page 查询范围 默认为0的情况查询所有数据
     * @param int $size 查询范围 默认为0的情况查询所有数据
     * @return array
     */
    public function listProductRelatedResource(int $resourceType = 0, int $relatedId = 0, int $productId = 0, int $resourceId = 0, array $select = [], int $page=0, int $size =0): array
    {
        $where = []; // 数据查询条件字段
        $binds = []; // 查询所绑定的数据
        if ($resourceType !== 0){
            $where[] = ' resource_type = ? ';
            $binds[] = $resourceType;
        }
        if ($relatedId !== 0){
            $where[] = ' id = ? ';
            $binds[] = $relatedId;
        }
        if ($productId !== 0){
            $where[] = ' product_id = ? ';
            $binds[] = $productId;
        }
        if ($resourceId !== 0){
            $where[] = ' resource_id = ? ';
            $binds[] = $resourceId;
        }
        return $this->selectByCondition(where: $where,binds: $binds,select: $select,page: $page,size: $size);
    }
}
