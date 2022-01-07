<?php
/**
 * User: 无畏泰坦
 * Date: 2022.01.06 10:47
 * Describe
 */

namespace Application\Domain\System;

use Application\Domain\BaseDomain;

/**
 * Created on 2022.01.06 10:47
 * Created by 无畏泰坦
 * Describe
 */
class Menu extends BaseDomain
{
    public $id;
    public $path;
    public $name;
    public $component;
    public $parent_id;
    public $create_time;
    public $name_zh;
    public $icon;


}
