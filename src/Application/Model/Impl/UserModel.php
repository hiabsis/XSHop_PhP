<?php
/**
 * User: 无畏泰坦
 * Date: 2021.12.15 17:11
 * Describe
 */

namespace Application\Model\Impl;

use Application\Constant\SystemConstants;
use Application\Domain\System\User;
use Application\Exception\ModelValidatorParamsException;
use Application\Helper\RedisHelper;
use Application\Model\UserModelInterface;
use Medoo\Medoo;
use PDO;

/**
 * Created on 2021.12.15 17:11
 * Created by 无畏泰坦
 * Describe
 */
class UserModel extends BaseModel implements UserModelInterface
{
    private  $redis;
    public function __construct(PDO $conn, Medoo $medoo,  RedisHelper $redisHelper)
    {
        parent::__construct($conn, $medoo);
        $this->redis = $redisHelper;
        $this->tableName = 'sys_user';
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 11:18
     * Describe 查询用户
     * @param array $select
     * @param array $queryCondition
     * @param array $limit
     * @param bool $isAnd
     * @return array
     */
    public function findUser(array $select = [], array $queryCondition = [], array $limit = [], bool $isAnd = true): array
    {
        if (empty($select)) {
            $select = '*';
        }
        $where = [];
        if (!$isAnd) {
            $where['OR'] = $this->buildQueryCondition($queryCondition);
        } else {
            $where = $this->buildQueryCondition($queryCondition);
        }
        if (!empty($limit)) {
            $where['LIMIT'] = $limit;
        }
        return $this->medoo->select($this->tableName, $select, $where);

    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 11:16
     * Describe  批量删除
     * @param array $ids
     * @return bool
     */
    public function removeUserByIds(array $ids = []): bool
    {
        if (empty($ids)) {
            return true;
        }
        $where['id'] = $ids;
        $stmt = $this->medoo->delete($this->tableName, $where);
        if ($stmt === null) {
            return false;
        }
        return $stmt->rowCount() > 0;
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 17:01
     * Describe 更新数据
     * @param array $updateDate
     * @param int $userId
     * @return bool
     */
    public function updateUserById(array $updateDate = [], int $userId = 0): bool
    {
        if (array_key_exists('id', $updateDate)) {
            throw  new ModelValidatorParamsException("非法参数 id,更新数据不能包含Id");
        }
        if (empty($updateDate)) {
            return false;
        }
        $where['id'] = $userId;
        $stmt = $this->medoo->update($this->tableName, $updateDate, $where);
        if ($stmt === null) {
            return false;
        }
        return $stmt->rowCount() > 0;
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 11:18
     * Describe 保存用户
     * @param array $saveData
     * @return bool
     */
    public function saveUser(array $saveData): bool
    {
        $this->medoo->insert($this->tableName, $saveData);
        return $this->medoo->id();
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 11:01
     * Describe 统计数据
     * @param array $query
     * @return int
     */
    public function countUser(array $query): int
    {
        $where = $this->buildQueryCondition($query);
        return $this->medoo->count($this->tableName, $where);
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 10:59
     * Describe 是否存在
     * @param string $username 用户名
     * @return bool
     */
    public function hasUser(string $username = ''): bool
    {
        $where = [];
        if (empty($username)) {
            return false;
        }
        $where['username'] = $username;
        return $this->medoo->has($this->tableName, $where);
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 11:36
     * Describe 获取单个用户
     * @param array $query
     * @param array $select
     * @return array
     */
    public function getUser(array $query,array $select = []): array
    {

        if (empty($select)){
            $select = '*';
        }
       return  $this->medoo->get($this->tableName,$select,$query);
    }

    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.12 16:23
     * @description：缓存用户信息
     * @modified By：
     * @version:     $version$
     */
    public function cacheUserInfo(array $user, string $token)
    {
        $this->redis->setHash($token,$user);
        // 设置过期时间
        $this->redis->expire($token,SystemConstants::$REDIS_USER_INFO_TIME_OUT);
        // TODO: Implement cacheUserInfo() method.
    }

    public function getCacheUserInfo(string $token):array
    {
        return $this->redis->getHash($token);
    }
    public function removeMenu(array $ids)
    {
        // TODO: Implement removeMenu() method.
    }
    public function removeUserInfoCache(string $token)
    {
        return $this->redis->deleteKey($token);
    }


    /**
     * User: 无畏泰坦
     * Date: 2021.12.29 16:59
     * Describe 构建查询条件
     * @param array $queryCondition
     * @return array
     */
    protected function buildQueryCondition(array $queryCondition): array
    {
        $where = [];
        if (empty($queryCondition)) {
            return [];
        }
        foreach ($queryCondition as $filed => $condition) {
            if (!empty($condition)) {
                switch ($filed) {
                    case 'id':
                    case 'username':
                    case 'password':
                    case 'salt':
                        $where[$filed] = $condition;
                }
            } else {
                throw  new ModelValidatorParamsException("查询条件为空");
            }
        }
        return $where;
    }


    public function getUserDetailInfo(string $username): array
    {
        return  [];
    }
}
