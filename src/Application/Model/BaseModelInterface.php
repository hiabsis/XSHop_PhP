<?php

namespace Application\Model;

interface BaseModelInterface
{
    /**
     * 开启数据集事务
     * @return void
     */
    public function startTransactional();

    /**
     * 事务回滚
     * @return void
     */
    public function rollback();
    /**
     * 事务提交
     * @return void
     */
    public function commit();
}
