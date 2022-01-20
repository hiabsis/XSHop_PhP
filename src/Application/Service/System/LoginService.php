<?php
/**
 * @author     ：无畏泰坦
 * @date       ：Created in 2022.01.19 11:17
 * @description：
 * @modified By：
 * @version:     1.0
 */

namespace Application\Service\System;
use Application\Constant\ErrorEnum;
use Application\Exception\BaseException;
use Application\Exception\CommonException;
use Application\Helper\JWTHelper;
use Application\Model\Impl\UserModel;
use Application\Model\UserModelInterface;
use Application\Service\BaseService;
use Application\Service\LoginServiceInterface;
use Application\Service\TokenServiceInterface;
use Application\Service\UserServiceInterface;

/**
 * @author     ：无畏泰坦
 * @date       ：Created in 2022.01.19 11:17
 * @description：${description}
 * @modified By： 登入服务
 * @version:     1.0
 */
class LoginService extends  BaseService implements LoginServiceInterface
{
    /**
     * @var UserModelInterface
     */
    private $userModel;

    /**
     * @var TokenServiceInterface
     */
    private $tokenService;

    private $userService;
    public function __construct(UserModelInterface $userModel,UserServiceInterface $userService, TokenServiceInterface $tokenService)
    {
        $this->userModel = $userModel;
        $this->tokenService = $tokenService;
        $this->userService = $userService;
    }

    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.19 13:06
     * @description：${description}
     * @modified By： 用户获取授权码
     * @version:     1.0 权限
     */
    public function authLogin(string $username,string $password):string {
        // 获取用户信息 用户名唯一标识
        $user = $this->userModel->getUser(['username'=>$username]);
        if (empty($user) ){
            // 数据库中找不到用户 账号码错误
            throw  new CommonException(errorInfo: ErrorEnum::$ERROR_20000);
        }
        // 密码比较
        $encodePassword =  $this->encodePassword($password,$user['salt']);
        if ($encodePassword['password'] !== $user['password'] ){
            return  new CommonException(errorInfo: ErrorEnum::$ERROR_20001);
        }
        $token =  $this->tokenService->generateToken($user);

        $userInfo = $this->userService->getUserDetailInfo($user['id']);
        $this->userModel->cacheUserInfo($userInfo,$token);
        return  $token;
    }


}