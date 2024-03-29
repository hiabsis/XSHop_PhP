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
        if ($query['page'] === "-1"||  $query['size'] ==="-1"){
            return  [];
        }
        $limit = [];
        $limit[] = ($query['page'] - 1) * $query['size'];
        $limit[] = $query['size'];
        if ($limit[0] > $total) {
            $limit[0] = $total;
        }
        return $limit;
    }


    protected function builderTreeResult(array $arr,int $page=-1,int $size =-1,int $rootId = -1,string $label= 'name'): TreeVO
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
        return  $this->handleTreeByPage($root,$page,$size);
    }
    protected function handleTreeByPage(TreeVO $root, int $page=-1,int $size =-1):TreeVO
    {

        $root->total =  $root->children === null ? 0:count($root->children);
        if ($root->children === null){
            $root->children = [];
            return  $root;
        }
        if (($page) === -1 || -1 === ($size)) {
            return $root;
        } else {
            $end = min($page * $size, count($root->children));
            $root->children = array_slice($root->children, ($page- 1) * $page, $end);
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

    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.13 14:31
     * @description：${description}
     * @modified By： 密码加密
     * @version:     1.0
     */
    public function  encodePassword(string $password,string $salt=""): array
    {
        if (empty($salt)){
            // 生成盐,默认长度 16 位
            $salt = substr(uuid(),0,16);
        }

        // 得到 hash 后的密码
        return ['password'=>md5($password.$salt),'salt'=> $salt];
    }


}
