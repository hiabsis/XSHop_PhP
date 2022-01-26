<?php

namespace Application\Model\Impl;

use Application\Constant\ModelConstants;
use Application\Exception\MysqlSaveException;
use Medoo\Medoo;
use PDO;

/**
 * User: 无畏泰坦
 * Date: 2021.12.16 10:49
 * Describe 基于MSQL持久化类，提供简单的数据CURE,
 */
abstract class BaseModel
{
    protected $conn;
    protected $tableName;
    protected $medoo;
    public function __construct(PDO $conn,Medoo $medoo)
    {
        $this->conn = $conn;
        $this->medoo = $medoo;
    }

    /**
     * 开启数据集事务
     * @return void
     */
    public function startTransactional(): void
    {
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conn->beginTransaction();
    }

    /**
     * 事务回滚
     * @return void
     */
    public function rollback(): void
    {

        $this->conn->rollBack();
    }

    /**
     * 事务提交
     * @return void
     */
    public function commit(): void
    {
        $this->conn->commit();
    }

    /**驼峰命名转下划线命名
     * @param string $str
     * @return string
     */
    protected function toUnderScore(string $str): string
    {
        $dstr = preg_replace_callback('/([A-Z]+)/', static function ($matchs) {
            return '_' . strtolower($matchs[0]);
        }, $str);
        return trim(preg_replace('/_{2,}/', '_', $dstr), '_');
    }

    /**
     * 下划线命名到驼峰命名
     * @param string $str
     * @return mixed|string
     */
    protected function toCamelCase(string $str):string
    {
        $array = explode('_', $str);
        $result = $array[0];
        $len = count($array);
        if ($len > 1) {
            for ($i = 1; $i < $len; $i++) {
                $result .= ucfirst($array[$i]);
            }
        }
        return $result;
    }

    /**
     * 生成占位符
     * @param int $total
     * @param int $rowSize
     * @return string   $total = 6, $rowSize = 3 '((?,?,?),(?,?,?))'
     */
    protected function generatePlaceHolder(int $total, int $rowSize): string
    {
        return implode(',', array_map(
            static function ($el) {
                return '(' . implode(',', $el) . ')';
            },
            array_chunk(array_fill(0, $total, '?'), $rowSize)));
    }



    protected function getTableColumsInfo():array
    {
        $sql = "SHOW COLUMNS FROM $this->tableName";
        $stmt = $this->conn->query($sql);
        $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_column($res, 'Field');
    }


    public function insert(object $obj):int
    {
        $tableColumns = $this->getTableColumsInfo();
        $binds = [];
        $columns = [];
        foreach ($obj as $k => $v) {
            $key = $this->toUnderScore($k);
            if (!empty(trim($v)) && in_array($key, $tableColumns, true)) {
                $columns[] = " `$key` ";
                $binds[] = $v;
            }
        }
        $sql = "insert into $this->tableName ";
        if (empty($binds) || empty($columns)) {
            return false;
        }
        $sql .= sprintf(" ( %s ) ", implode(' , ', $columns));
        $sql .= sprintf(" VALUES  %s ", $this->generatePlaceHolder(count($binds), count($binds)));
        $query = $this->conn->prepare($sql);
        $query->execute($binds);
        $id =  (int) $this->conn->lastInsertId();
        if ($id === 0){
            throw  new MysqlSaveException('数据库数据保存失败');
        }
        return  $id;
    }

    public function insertBatch(array $arr): bool
    {
        $tableColumns = $this->getTableColumsInfo();
        $binds = [];
        $columns = [];
        foreach ($arr[0] as $k => $v) {
            $key = $this->toUnderScore($k);
            if (!empty(trim($v)) && in_array($key, $tableColumns, true)) {
                $columns[] = " `$key` ";
            }
        }
        foreach ($arr as $obj) {
            foreach ($obj as $k => $v) {
                $key = $this->toUnderScore($k);
                if (!empty(trim($v)) && in_array($key, $tableColumns, true)) {
                    $binds[] = $v;
                }
            }
        }

        $sql = "insert into $this->tableName ";
        if (empty($binds) || empty($columns)) {
            return false;
        }
        $sql .= sprintf(" ( %s ) ", implode(' , ', $columns));
        $sql .= sprintf(" VALUES  %s ", $this->generatePlaceHolder(count($binds), count($binds) / count($arr)));

        $query = $this->conn->prepare($sql);
        return ($query->execute($binds));
    }

    public function update(object $obj): bool
    {
        $tableColumns = $this->getTableColumsInfo();
        $set = [];
        $binds = [];
        foreach ($obj as $k => $v) {
            $key = $this->toUnderScore($k);
            if (!empty(trim($v)) && $key !== 'id' && in_array($key, $tableColumns, true)) {
                $set[] = " `$key`  = ? ";
                $binds[] = $v;
            }
        }
        $where = ['id = ?'];
        $binds[] = $obj->id;
        $sql = "update $this->tableName ";
        if (!empty($binds)) {
            $sql .= sprintf(" SET %s", implode(' , ', $set));
        }
        if (!empty($where)) {
            $sql .= sprintf(" WHERE %s", implode(' ', $where));
        }
        $query = $this->conn->prepare($sql);
        return ($query->execute($binds));
    }

    public function deleteIds(array $ids): bool
    {
        $sql = "delete from $this->tableName where id in (%s) ";
        $sql = sprintf($sql, implode(' , ', array_fill(0, count($ids), " ? ")));
        $query = $this->conn->prepare($sql);
        $query->execute($ids);
        return ($query->execute($ids));
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.29 14:22
     * Describe 查询数据表的数据
     * @param array $where
     * @param array $binds
     * @param int $connectType
     * @param array $select
     * @param int $page
     * @param int $size
     * @param string $clazz
     * @return array
     */
    public function findByCondition(array $where = [], array $binds=[], int $connectType = 0,array $select = [],int $page=0,int $size=0,string $clazz=''): array
    {

        $offset = ($page - 1) * $size;
        $sql = "select   ";
        if (empty($select)) {
            $sql .= "  * from $this->tableName ";
        } else {
            $sql .= sprintf("  %s from $this->tableName ", implode(' , ', $select));
        }
        if (empty($condition['condition']) || 'OR' === $condition['condition']) {
            if (!empty($where)) {
                $sql .= sprintf(" WHERE %s", implode(' OR ', $where));
            }
        } else if (!empty($where)) {
            $sql .= sprintf(" WHERE %s", implode(' AND ', $where));
        }

        $sql .= "  order by create_time ";
        if (!empty($page) && !empty($size)) {
            $sql .= "limit $size offset $offset";
        }
        $query = $this->conn->prepare($sql);
        $query->execute($binds);
        $res = [];
        while ($result = $query->fetch(\PDO::FETCH_ASSOC)) {
            $temp = new $clazz();
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
     * Date: 2021.12.30 13:41
     * Describe 映射请求结果
     * @param $data
     * @param $clazz
     * @return array
     */
    protected function mapperResult($data,$clazz): array
    {
        $res = [];
       foreach ($data as $d){
           $instance = new $clazz();
           foreach ($d as $k => $v) {
               $tansferKey = $this->toCamelCase($k);
               $instance->$tansferKey = $v;
           }
           $res[] = $instance;
       }

        return $res;
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.29 11:47
     * Describe 查询数据库中的数据，并把数据库查询的数据字段转为驼峰命名
     * @param array $where 查询条件
     * @param array $binds 绑定值
     * @param int $connectType 查询条件连接类型
     * @param array $select 查询字段
     * @param int $page 分页查询
     * @param int $size 分页查询
     * @return array
     */
    public function selectByCondition(array $where = [], array $binds=[], int $connectType = 0,array $select = [],int $page=0,int $size=0): array
    {

        $sql = "select   ";
        if (empty( $select)) {
            $sql .= "  * from $this->tableName ";
        } else {
            $sql .= sprintf("  %s from $this->tableName ", implode(' , ', $select));
        }
        if ($connectType === ModelConstants::$AND_CONNECTOIN && !empty($where) ) {
            $sql .= sprintf(" WHERE %s", implode(' OR ', $where));
        } else if (!empty($where)) {
            $sql .= sprintf(" WHERE %s", implode(' AND ', $where));
        }
        $sql .= "  order by create_time desc ";
        if (!empty($page) && !empty($size)) {
            $offset = ($page - 1) *$size;
            $sql .= "limit $size offset $offset";
        }
        $query = $this->conn->prepare($sql);
        $query->execute($binds);
        $res = [];
        while ($result = $query->fetch(\PDO::FETCH_ASSOC)) {
            $temp = [];
            foreach ($result as $k => $v) {
                $temp[$this->toCamelCase($k)]= $v;
            }
            $res[] = $temp;
        }
        return $res;
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.16 11:09
     * Describe 统计查询的数据的总数 按照查询数据的主键统计 id
     * @param array $condition 查询条件
     * @return int
     */
    public function countByCondition(array $condition): int
    {

        $sql = "select count(id) as number from $this->tableName ";
        // 有参数统计
        if (!empty($condition['where'])) {
            $sql .= sprintf(" WHERE %s", implode(' OR ', $condition['where']));
            $query = $this->conn->prepare($sql);
            $query->execute($condition['binds']);
        } else {
            // 无参数统计
            $query = $this->conn->query($sql);
        }
        return $query->fetch()['number'];
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.17 13:23
     * Describe 自定义删除
     * @param array $deleteCondition
     * @return bool
     */
    public function deleteByCondition(array $deleteCondition): bool
    {
        $sql = sprintf("delete from $this->tableName where %s ",implode(' and ' ,$deleteCondition['where']) );
        $query = $this->conn->prepare($sql);
        return $query->execute($deleteCondition['binds']);
    }

    protected function buildQueryCondition(array $queryCondition): array
    {
        return  [];
    }

    protected function updateByMedoo(array $updateArr,array $where){

    }


}
