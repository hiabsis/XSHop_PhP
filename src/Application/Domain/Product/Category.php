<?php

namespace Application\Domain\Product;
use Application\Domain\BaseDomain;

/**
 * 商品分类
 */
class Category extends BaseDomain
{
    // ID
    public   $id;
    // 类别
    public   $name;
    // 父ID
    public  $parentId;
    // 分类状态
    public  $status;
    //类型
    public $type;
    // 创建时间
    public   $createTime;
    //
    public $sort;
    public $level;
}
