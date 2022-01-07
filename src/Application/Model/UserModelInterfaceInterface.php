<?php

namespace Application\Model;

use Application\Domain\System\User;

interface UserModelInterfaceInterface extends BaseModelInterface
{


    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 9:23
     * Describe 查找用户
     * @param array $queryCondition
     * @param array $column
     * @return mixed
     */
    public function findUser(array $queryCondition,array $column):array;

    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 11:16
     * Describe  批量删除
     * @param array $ids
     * @return bool
     */
    public function removeUserByIds(array $ids = []): bool;

    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 17:01
     * Describe 更新数据
     * @param array $updateDate
     * @param int $userId
     * @return bool
     */
    public function updateMenuById(array $updateDate = [], int $userId = 0): bool;

    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 11:01
     * Describe 统计数据
     * @param array $query
     * @return int
     */
    public function countUser(array $query): int;

    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 10:59
     * Describe 是否存在
     * @param string $username 用户名
     * @return bool
     */
    public function hasUser(string $username = ''): bool;
    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 9:26
     * Describe 保存用户信息
     * @param array $saveData
     * @return bool
     */
    public function saveUser(array $saveData):bool;

    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 11:36
     * Describe 获取单个用户
     * @param array $query
     * @return array
     */
    public function getUser(array $query): array;


}
