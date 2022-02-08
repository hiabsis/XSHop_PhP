<?php
/**
 * @author     ：无畏泰坦
 * @date       ：Created in 2022.01.27 15:16
 * @description：
 * @modified By：
 * @version:     1.0
 */

namespace Application\Constant;

class CartConstance
{
    //  新加入购物车成功
    public static $SAVING_SUCCESE = 1;
    //  该商品已经在购物车，数量+1
    public static $ADD_SUCCESE = 2;
    // 商品数量达到限购数量
    public static $NOT_ALLOWING = 3;
    // 最多添加数量
    public static $MAX_ALLWING_NUM = 10;

}