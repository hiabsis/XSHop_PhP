<?php

namespace Application\Domain\Product;

use Application\Domain\BaseDomain;

class ProductRelatedResource extends BaseDomain
{
    public $id ;
    public $productId;
    public $resourceId;
    public $resourceType;
}
