<?php


namespace Application\Controller\UserApi;

use Application\Controller\BaseController;
use Application\Domain\Response\Result;
use Application\Domain\Settings\ValidatorRuleInterface;

use Application\Service\LoginServiceInterface;
use Application\Service\TokenServiceInterface;
use Application\Service\UserServiceInterface;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

/**
 * Created on 2022.01.07 10:47
 * Created by 无畏泰坦
 * Describe
 */
class LoginController extends BaseController
{
    /**
     * @var LoginServiceInterface
     */
    private  $loginService;
    private  $userService;


    public function __construct(LoggerInterface $logger, ValidatorRuleInterface $rule, TokenServiceInterface $tokenService,LoginServiceInterface $loginService,UserServiceInterface $userService)    {
        parent::__construct($logger, $rule, $tokenService);
        $this->class = self::class;
        $this->loginService = $loginService;
        $this->userService = $userService;

    }


    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 11:41
     * Describe 登入
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws JsonException
     */
    public function authLogin(Request $request, Response $response, array $args) :Response
    {
        // 参数校验
        $this->hasAllRequiredParams($request,"authLogin");
        $username = $this->getParamsByName('username');
        $password = $this->getParamsByName('password');
        $jwtToken = $this->loginService->authLogin($username,$password);
        return $this->respondWithJson(Result::SUCCESS(message: $jwtToken),$response);
    }


    public function getUserInfo(Request $request, Response $response, array $args) :Response
    {
        $jwtToken = $this->getRequestToken($request);

        $userInfo =  $this->tokenService->getUserInfo($jwtToken);
        return $this->respondWithJson(Result::SUCCESS($userInfo),$response);
    }

    /**
     * @throws JsonException
     */
    public function logout(Request $request, Response $response, array $args) :Response
    {
        $jwtToken = $this->getRequestToken($request);
        $this->tokenService->invalidateToken($jwtToken);
        return $this->respondWithJson(Result::SUCCESS(message: "注销成功"),$response);

    }

    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.13 14:13
     * @description：${description}
     * @modified By：用户注册
     * @version:     1.0
     */
    public function register(Request $request, Response $response, array $args) :Response
    {

        $this->hasAllRequiredParams($request,"register");
        $username = $this->getParamsByName('username');
        $password = $this->getParamsByName('password');
        $exist = $this->userService->isExist($username);
        if ($exist) {
            return $this->respondWithJson(Result::FAIL(message: "用户名重复"),$response);
        }

        $id = $this->userService->register($username,$password);
        if (empty($id)){
            return $this->respondWithJson(Result::FAIL(message: "注册失败"),$response);
        }
        return $this->respondWithJson(Result::SUCCESS(message: "注册成功"),$response);
    }



}
