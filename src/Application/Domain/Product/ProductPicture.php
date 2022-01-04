<?php

namespace  Application\Domain\Product;

/**
 * 商品图片信息
 */
class ProductPicture
{
    public   $id;
    // 商品_id
    public   $userId;
    // 保存位置
    public   $productId;
    // 状态
    public   $pictureUrl;
    // 类型
    public   $pictureSize;

    public  $type;
    // 用户ID
    public   $pictureLocalPath;
    // 创建时间
    public   $createTime;



}