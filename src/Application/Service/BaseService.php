<?php
/**
 * User: 无畏泰坦
 * Date: 2022.01.06 10:53
 * Describe
 */

namespace Application\Service;

use Application\Constant\ProductConstants;
use Application\Domain\VO\TreeVO;
use Application\Helper\ClassHelper;
use Application\Helper\UploadHelper;

/**
 * Created on 2022.01.06 10:53
 * Created by 无畏泰坦
 * Describe
 */
abstract class BaseService
{
    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 18:06
     * Describe 获取分页查询的参数
     * @param array $query
     * @param int $total
     * @return array
     */
    protected function getPageParams(array $query, int $total): array
    {
        $limit = [];
        $limit[] = ($query['page'] - 1) * $query['size'];
        $limit[] = $query['size'];
        if ($limit[0] > $total) {
            $limit[0] = $total;
        }
        return $limit;
    }


    protected function builderTreeResult(array $arr,array $query,int $rootId = -1,string $label= 'name'): TreeVO
    {
        $root = new TreeVO();
        $map = [];
        foreach ($arr as $item) {
            $parentId = $item['parent_id'];
            if (array_key_exists($parentId, $map)) {
                $temp =  &$map[$parentId];
                $temp[] = $item;
                $map[$parentId] = $temp;
            } else {
                $map[$parentId] = array($item);
            }
        }

        $root->id = $rootId;
        $root->label = "根目录";
        $this->buildTree($map, $rootId, $root,$label);
        return  $this->needPage($root,$query);
    }
    protected function needPage(TreeVO $root, array $query):TreeVO
    {

        $root->total =  $root->children === null ? 0:count($root->children);
        if ($root->children === null){
            $root->children = [];
            return  $root;
        }
        if (!array_key_exists('page',$query) ||$query['page'] === -1 || $query['size']===-1) {
            return $root;
        } else {
            $end = min($query['page'] * $query['size'], count($root->children));
            $root->children = array_slice($root->children, ($query['page'] - 1) * $query['size'], $end);
            return $root;
        }
    }
    /**
     * User: 无畏泰坦
     * Date: 2022.01.06 11:47
     * Describe 递归构建树
     * @param $map
     * @param $parentId
     * @param TreeVO $root
     */
    protected function buildTree(&$map, $parentId, TreeVO $root,$label): void
    {

        if (!array_key_exists($parentId, $map)) {
            return;
        }
        $children =  &$map[$parentId];
        $root->children = [];
        foreach ($children as $child) {
            $node = new TreeVO();
            $node->label = $child[$label];
            $node->id = $child['id'];
            $node->detail = $child;
            $root->children[] = $node;
            $this->buildTree($map, $child['id'], $node,$label);
        }
        $root->total = count($root->children);
    }

}
