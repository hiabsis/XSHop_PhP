<?php
/**
 * User: 无畏泰坦
 * Date: 2021.12.15 16:55
 * Describe
 */

namespace Application\Service\System;

use Application\Constant\ErrorEnum;
use Application\Domain\System\User;
use Application\Domain\VO\TreeVO;
use Application\Exception\CommonException;
use Application\Exception\LoginException;
use Application\Exception\ModelException;
use Application\Exception\ServiceException;
use Application\Exception\ServiceValidatorParamsException;
use Application\Helper\JWTHelper;
use Application\Model\ApiMenuModelInterface;
use Application\Model\ApiModelInterface;
use Application\Model\MenuModelInterface;
use Application\Model\RoleMenuModelInterface;
use Application\Model\UserModelInterface;
use Application\Model\UserRoleModelInterface;
use Application\Service\BaseService;
use Application\Service\UserServiceInterface;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

/**
 * Created on 2021.12.15 16:55
 * Created by 无畏泰坦
 * Describe
 */
class UserService extends  BaseService implements UserServiceInterface
{
    private $userModel;
    private $userRoleModel;
    private $menuModel;
    private $roleMenuModel;
    private $apiModel;
    private $apiMenuModel;
    public function __construct(UserModelInterface $userModel,
                                UserRoleModelInterface $userRoleModel,
                                MenuModelInterface $menuModel,
                                ApiMenuModelInterface $apiMenuModel,
                                ApiModelInterface $apiModel,
                                RoleMenuModelInterface $roleMenuModel)
    {
        $this->userModel = $userModel;
        $this->userRoleModel = $userRoleModel;
        $this->menuModel = $menuModel;
        $this->roleMenuModel = $roleMenuModel;
       $this->apiMenuModel=  $apiMenuModel;
       $this->apiModel = $apiModel;
    }



    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 17:43
     * Describe 分页查询菜单
     * @param array $query
     * @return array
     */
    #[ArrayShape(['total' => "int", 'data' => "array"])] public function listUserByPage(array $query = []): array
    {
        $total = $this->userModel->countUser($query);
        $pageParams = $this->getPageParams($query,$total);
        $data = $this->userModel->findUser(queryCondition: $query,limit: $pageParams);
        return ['total' => $total,'data' => $data];
    }



    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe
     * @param array $ids
     * @return bool
     */
    public function removeMenu(array $ids): bool
    {
       return $this->userModel->removeMenu($ids);
    }
    /**
     * User: 无畏泰坦
     * Date: 2022.01.05 18:12
     * Describe 数据保存
     * @param array $user
     * @return int
     */
    public function register(string $username,string $password): int
    {
        $user = $this->encodePassword($password);
        $user['username'] = $username;
       return $this->userModel->saveUser($user);
    }

    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.13 14:31
     * @description：${description}
     * @modified By：
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
    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 11:24
     * Describe 获取用户
     * @param string $username
     * @param string $password
     * @return array
     */
    public function getUser(string $username, string $password):array
    {
        return  $this->userModel->getUser(['username'=>$username,'password'=>$password]);
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 14:30
     * Describe
     * @param array $user
     * @param $userId
     * @return bool
     */
    public function updateUser(array $user,$userId):bool
    {
        return $this->userModel->updateUserById($user,$userId);
    }

    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.12 16:28
     * @description：${description}
     * @modified By： 用户登入
     * @version:     1.0
     */
    public function login(string $username,string$password) : string
    {

        // 检查用户是密码
        $user = $this->userModel->getUser(['username'=>$username]);
        $encodePassword =  $this->encodePassword($password,$user['salt']);
        if (empty($user)){
            return "";
        }
        if ($encodePassword['password'] !== $user['password'] ){
            return "";
        }
        $token = uuid();
        $this->userModel->cacheUserInfo($user,$token);
        $payload = [
            'token'=>$token,
            'userInfo' => [
                'username'=>$username,
                'id'=>$user['id'],
            ]
        ];
        return JWTHelper::encode($payload);
    }

    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.13 11:16
     * @description：${description}
     * @modified By：
     * @version:     1.0
     */
    public function checkJwtTokenInfo(string $jwtToken)
    {
        // 解析JWTToken
        $payload  = JWTHelper::decode($jwtToken);
        $jwt = JWTHelper::encode(payload:(array)$payload);
        if ($jwt !== $jwtToken){
            return false;
        }
        $userInfo = $this->userModel->getCacheUserInfo($payload->token);
        if (empty($userInfo)){
            return false;
        }

        return true;
        // 校验JWT是否被串改

    }
    public function isExist(string $username):bool
    {
       return $this->userModel->hasUser($username);
    }


    public function logout(string $jwtToken): bool
    {
        $payload  = JWTHelper::decode($jwtToken);
        $token = $payload->token;
        return $this->userModel->removeUserInfoCache($token);

    }
    /**
     * 加载当前用户的菜单
     * 根据用户 id 查询出该用户对应所有角色的 id
     * 根据这些角色的 id，查询出所有可访问的菜单项
     * 根据 parentId 把子菜单放进父菜单对象中，整理返回有正确层级关系的菜单数据
     */
    #[Pure] public function getMenusTreeByCurrentUserId(int $currentUserId):TreeVO
    {

        if (empty($currentUserId)){
            throw  new CommonException(errorInfo: ErrorEnum::$ERROR_20011);
        }
        // 获得当前用户对应的所有角色的 id 列表
        $userRoles = $this->userRoleModel->findUserRole(select: ['role_id'], queryCondition: ['user_id'=>$currentUserId]);
        // 查询出这些角色对应的所有菜单项
        $roleIds = [];
        foreach ($userRoles as $userRole){
            $roleIds[] = $userRole['role_id'];
        }
        $roleMenus = $this->roleMenuModel->findRoleMenu(select:['menu_id'],queryCondition: ['role_id'=> $roleIds]);
        $menuIds = [];
        foreach ($roleMenus as $roleMenu){
            $menuIds[] = $roleMenu['menu_id'];
        }
        $menus = $this->menuModel->findMenu(queryCondition: ["id"=>$menuIds]);
        // 处理菜单项的结构
        return  $this->builderTreeResult($menus);
    }


    /**
     * @return  array => [
     *   int user_id  用户ID
     *   string username 用户名
     *   string nickname 用户昵称
     *   array role_ids  角色
     *   array menus     页面
     *   array permissions 权限
     * ]
     * @throws \JsonException
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.19 15:01
     * @description：${description}
     * @modified By：
     * @version:     1.0
     */
    public function getUserDetailInfo(int $userId) :array
    {

        $res = [];
        if (empty($userId)){
            throw  new ServiceValidatorParamsException("无法获取当前登入用户");
        }
        $user = $this->userModel->getUser(['id'=>$userId]);
        $res['user_id'] = $user['id'];
        $res['username'] = $user['username'];
        $res['nickname'] = $user['nickname'];

        // 获得当前用户对应的所有角色的 id 列表
        $userRoles = $this->userRoleModel->findUserRole(select: ['role_id'], queryCondition: ['user_id'=>$userId]);
        // 查询出这些角色对应的所有菜单项
        $roleIds = [];
        foreach ($userRoles as $userRole){
            $roleIds[] = $userRole['role_id'];
        }
        $res['role_ids'] = json_encode($roleIds, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);;
        $roleMenus = $this->roleMenuModel->findRoleMenu(select:['menu_id'],queryCondition: ['role_id'=> $roleIds]);
        $menuIds = [];
        foreach ($roleMenus as $roleMenu){
            $menuIds[] = $roleMenu['menu_id'];
        }
        $menus = $this->menuModel->findMenu(queryCondition: ["id"=>$menuIds]);
        // 处理菜单项的结构
        $menusTree =   $this->builderTreeResult($menus);
        $res['menus'] = json_encode($menusTree, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        // 查询出这些页面需要的权限
        $apiMenus = $this->apiMenuModel->findApiMenu(queryCondition:['menu_id'=>$menuIds]);
        $apiIds = [];
        foreach ($apiMenus as $apiMenus){
            $apiIds[] = $apiMenus['api_id'];
        }
        if (empty($apiIds)){
            $res['permission'] = [];
        }else{
            $permissions = $this->apiModel->findApi(select:['permission'],queryCondition:['id'=>$apiIds]);
            $p = [];
            foreach ($permissions as $permission){
                $p[] = $permission['permission'];
            }
            $p = array_unique($p);
            $res['permissions'] =  json_encode($p, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        }

        return $res;
    }

}
