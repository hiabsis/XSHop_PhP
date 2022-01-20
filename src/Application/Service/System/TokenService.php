<?php
/**
 * @author     ：无畏泰坦
 * @date       ：Created in 2022.01.19 11:22
 * @description：
 * @modified By：
 * @version:     1.0
 */

namespace Application\Service\System;

use Application\Constant\ErrorEnum;
use Application\Constant\SystemConstants;
use Application\Exception\CommonException;
use Application\Helper\JWTHelper;
use Application\Model\ApiModelInterface;
use Application\Model\UserModelInterface;
use Application\Service\BaseService;
use Application\Service\TokenServiceInterface;
use Application\Service\UserServiceInterface;
use JsonException;

class TokenService extends BaseService implements TokenServiceInterface
{
    /**
     * @var UserServiceInterface
     */

    private $userModel;

    private $apiModel;
    public function __construct(UserModelInterface $userModel,ApiModelInterface$apiModel)
    {
        $this->userModel = $userModel;
        $this->apiModel = $apiModel;
    }

    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.19 13:22
     * @description：${description}
     * @modified By： 用户登录验证通过后(sso/帐密),生成token
     * @version:     1.0
     */
    public function generateToken(array $user): string
    {
        $token = uuid();
        $payload = [
            'token' => $token,
            'user_id' => $user['user_id']
        ];
        return JWTHelper::encode($payload);
    }

    /**
     * @throws JsonException
     */
    public function getUserInfo(string $token): array
    {

        if (!$this->checkToken($token)){
            throw  new CommonException(errorInfo: ErrorEnum::$ERROR_401);
        }
        $userInfo = $this->userModel->getCacheUserInfo($token);

        if (empty($userInfo) || count($userInfo) === 1){
            throw  new CommonException(errorInfo: ErrorEnum::$ERROR_20011);
        }
        $userInfo['permissions'] = json_decode($userInfo['permissions'], true, 512, JSON_THROW_ON_ERROR);
        $userInfo['menus'] = json_decode($userInfo['menus'], false, 512, JSON_THROW_ON_ERROR);
        $userInfo['role_ids'] = json_decode($userInfo['role_ids'], true, 512, JSON_THROW_ON_ERROR);
        return  $userInfo;
    }

    public function checkToken($token): bool
    {
        $payload = JWTHelper::decode($token);
        $jwt = JWTHelper::encode(payload: (array)$payload);
        if ($jwt !== $token) {
            return false;
        }
        return true;
    }

    /**
     * 退出登录时,将token置为无效
     */
    public function invalidateToken(string $token)
    {

        return $this->userModel->removeUserInfoCache($token);
    }

    /**
     * @throws JsonException
     */
    public function checkPermission(string $url, string $token):bool
    {
        $api = $this->getApiInfo($url);
        if ($api['type'] === SystemConstants::$API_TYPE_OPENING){
            return  true;
        }
        if ($api['type'] === SystemConstants::$API_TYPE_NOT_EXITS){
            throw new CommonException(errorInfo: ErrorEnum::$ERROR_410);
        }
        if (empty($token)){
            throw  new CommonException(errorInfo: ErrorEnum::$ERROR_20011);
        }
        $userInfo = $this->getUserInfo($token);

        return  $this->hasPermission($userInfo['permissions'],$api['permission']);
    }

    private function hasPermission(array $permissions,string $permission):bool
    {
        foreach ($permissions as $p){
            if ($p === $permission){
                return  true;
            }
        }
        return  false;
    }



    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.19 17:07
     * @description：${description}
     * @modified By：
     * @version:     1.0
     */
    private function getApiInfo(string $uri)
    {
        // 从缓存中获取
        $api  = $this->apiModel->getCacheApi($uri);
        //
        if ( $api['type'] === SystemConstants::$API_TYPE_NOT_EXITS){
            throw  new CommonException(errorInfo: ErrorEnum::$ERROR_410);
        }
        if (empty($api) ){
            $api = $this->apiModel->getApi(select: ['path','permission','status','type'],queryCondition: ['path'=>$uri,'status' => SystemConstants::$API_STATUS_COMMON]);
            if (empty($api)){
                $cache = ['type'=>SystemConstants::$API_TYPE_NOT_EXITS];
                throw  new CommonException(errorInfo: ErrorEnum::$ERROR_410);
            }
            $this->apiModel->cacheAllApi($uri,$api);
        }

        return $api;
    }

}