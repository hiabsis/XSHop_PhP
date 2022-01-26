<?php
/**
 * @author     ：无畏泰坦
 * @date       ：Created in 2022.01.19 15:17
 * @description：
 * @modified By：
 * @version:     1.0
 */

namespace Application\Model;

interface ApiModelInterface
{

    public function findApi(array $select = [], array $queryCondition = [], array $limit = [], bool $isAnd = true):array;
    public function countApi(array $queryCondition = []);
    public function getApi(array $select = [], array $queryCondition = []);
    public function updateApiById(array $updateDate = [], int $RoleId = 0): bool;
    public function cacheAllApi(string $key,array $data);
    public function removeApiByIds(array $ids = []): bool;
    public function saveApi(array $saveData): bool;
    public function getCacheApi(string $uri):array;


}