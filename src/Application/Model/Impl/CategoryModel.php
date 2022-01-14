<?php

namespace Application\Model\Impl;

use Application\Domain\Product\Category;
use Application\Exception\ModelValidatorParamsException;
use Application\Helper\ClassHelper;
use Application\Model\Impl\BaseModel;
use Medoo\Medoo;
use PDO;

class CategoryModel extends BaseModel implements \Application\Model\CategoryModelInterface
{

   public function __construct(PDO $conn, Medoo $medoo)
   {
       parent::__construct($conn, $medoo);
       $this->tableName = 'shop_product_category';
   }


    public function saveCategory(Category $category): int
    {
        return $this->insert($category);
    }



    public function countCategory(array $queryCondition): int
    {
       $where = [];
        if (!empty($queryCondition)){
            $where = $this->buildQueryCondition($queryCondition);
        }
        return $this->medoo->count($this->tableName,$where);
    }

    public function getCategory(array $queryCondition = [], array $select = []): array
    {
        // 查询条件
        $where = [];
        if (empty($select)){
            $select = "*";
        }
        if (!empty($queryCondition)){
            $where = $this->buildQueryCondition($queryCondition);
        }
        $where['LIMIT'] = [0,1];
        return $this->medoo->select($this->tableName, $select,$where);

    }

    public function listCategory(array $queryCondition = [],array $select = [],array $limit = []):array
    {
        // 查询条件
        $where = [];
        if (empty($select)){
            $select = "*";
        }
        if (!empty($queryCondition)){
            $where = $this->buildQueryCondition($queryCondition);
        }
        $categories =$this->medoo->select($this->tableName, $select,$where);

        return $this->mapperResult($categories,Category::class);

    }

    public function getCategoryAll(Category $category):array
    {

        $where = [];
        $binds = [];
        foreach ($category as $k => $v) {
            if (!empty(trim($v))) {
                switch ($this->toCamelCase($k)) {
                    case 'name':
                        $keywords = sprintf('%%%s%%', $v);
                        $where[] = $k . ' LIKE  ? ';
                        $binds[] = $keywords;
                        break;
                    case  'id':
                    case 'status':
                    case 'type':
                        $where[] = $k . ' =  ? ';
                        $binds[] = $v;
                }
            }
        }
        return $this->findByCondition( where: $where, binds: $binds, clazz: Category::class);
    }

    public function removeCategoryByIds(array $ids):bool
    {
       return $this->deleteIds($ids);
    }

    public function updateProductById(Category $category):bool
    {
        if (empty($category->id)) {
            throw new ModelValidatorParamsException("更新商品时Id为空");
        }
        return $this->update($category);
    }

    public function getCategoryById(int $id): Category
    {
        $arr =  $this->findByCondition( where: [' id = ? '], binds: [$id],  clazz:  Category::class);

        if (empty($arr)){
            return new Category();
        }
        return $arr[0];
    }
    public function getCategoryAllByParentId(int $parentId): array
    {
        if ($parentId<-1){
            throw  new ModelValidatorParamsException("getCategoryAllByParentId parentId must be >= -1");
        }
       return   $this->findByCondition( where: [' parent_id = ? '], binds: [$parentId],  clazz:  Category::class);


    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.29 16:59
     * Describe 构建查询条件
     * @param array $queryCondition
     * @param array $where
     * @return array
     */
    protected function buildQueryCondition(array $queryCondition): array
    {
        $where = [];
        if (empty($queryCondition)){
            return [];
        }
        foreach ($queryCondition as $filed => $condition) {
            if (!empty($condition)) {
                if ($filed === 'id') {
                    $where['id'] = (string)$condition;
                } else if ($filed === 'category_id') {
                    $where['name[~]'] = $condition;
                } else if ($filed === 'parent_id') {
                    $where['parent_id'] = $condition;
                } else if ($filed === 'status') {
                    $where['status'] = $condition;
                } else if ($filed === 'type') {
                    $where['type'] = $condition;
                } else if ($filed === 'level') {
                    $where['level'] = $condition;
                }
            } else {
                throw  new ModelValidatorParamsException("查询条件为空");
            }
        }
        return $where;
    }
}
