<?php

namespace  Application\Domain\Product;

/**
 * 商品信息
 */
class Product
{
    public  $id ;
    // 商家编号
    public  $userId;
    // 商品状态 2 上架 1 未上架
    public  $status;
    // 商品分类编号
    public  $categoryId;
    // 商品名称
    public  $name;
    // 商品简介
    public  $desc;
    // 库存数量
    public  $number;
    // 商品价格
    public  $price;
    public  $detail;
    // 创建时间
    public  $createTime;



}
