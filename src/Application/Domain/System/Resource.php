<?php
namespace Application\Domain\System;
use Application\Domain\BaseDomain;

class Resource extends BaseDomain
{
    public $id;
    public $userId;
    public $size;
    public $name;
    public $url;
    public $mine;
    public $createTime;
}
