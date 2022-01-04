<?php

namespace Application\Domain\VO;

/**
 * 树形结构组件
 */
class TreeVO
{
    public $id;
    // 标签名称
    public $label;
    // 子节点
    public $children;
    // 详细信息
    public $detail;

    public $total;
}
