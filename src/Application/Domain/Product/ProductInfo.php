<?php

namespace  Application\Domain\Product;

use Application\Domain\BaseDomain;

class ProductInfo extends BaseDomain
{
    public $id;
    public $productId;
    // 产品规格名称
    public $name;
    public $value;
    public $type;
    public $createTime;

}
