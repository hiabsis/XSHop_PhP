<?php
namespace Application\Constant;


/**
 * Created on 2021.12.28 15:48
 * Created by 无畏泰坦
 * Describe 商品产量
 */
class ProductConstants
{
    // 商品分类的根节点
     public static   $PRODUCT_CATEGORY_ROOT_ID = -1;
    // 普通商品分类
    public static   $PRODUCT_CATEGORY_CON_TYPE = 0;
    // 首页展示的商品分类
    public static $PRODUCT_CATEGORY_INDEX_TYPE = 1;

    // 商品状态 未发布
    public static $PRODUCT_STATUS_OFF = 1;
    // 已发布
    public static $PRODUCT_STATUS_ON = 2;

    // 商品图片类型_推荐图
    public static $PRODUCT_BANNE_IMAGE_TYPE = 1;
    // 商品图片类型_首页图
    public static $PRODUCT_IMAGE_TYPE_SHOW = 2;
    // 商品图片类型_详情图_轮播图
    public static $PRODUCT_IMAGE_TYPE_DETAIL = 3;
    // 分类图标
    public static $PRODUCT_CATEGORY_IMAGE_TYPE_ICON = 4;
    // 分类大图
    public static $PRODUCT_CATEGORY_IMAGE_TYPE_BIGICON = 5;
}
