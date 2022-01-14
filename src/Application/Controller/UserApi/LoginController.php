<?php


namespace Application\Controller\UserApi;

use Application\Controller\BaseController;
use Application\Domain\Response\Result;
use Application\Domain\Settings\ValidatorRuleInterface;
use Application\Helper\JWTHelper;
use Application\Helper\RedisHelper;
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
     * @var UserServiceInterface
     */
    private $userSerice;
    public function __construct(LoggerInterface $logger, ValidatorRuleInterface $rule,UserServiceInterface $userService)
    {
        parent::__construct($logger, $rule);
        $this->class = self::class;
        $this->userSerice = $userService;
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
    public function login(Request $request, Response $response, array $args) :Response
    {
//        $jwtToken = $request->getHeader('Authorization');
        $jwtToken = $request->getCookieParams()['token'];

        if (!empty($jwtToken)){
            if ( $this->userSerice->checkJwtTokenInfo($jwtToken)){
                return  $this->respondWithJson(Result::SUCCESS($jwtToken),$response);
            }
        }

        $this->validatorByName($request,"login");
        $username = $this->getParamsByName('username');
        $password = $this->getParamsByName('password');
        /**
         * 检查用户密码
         */

        $jwtToken = $this->userSerice->login($username,$password);
        $data['token'] = $jwtToken;
        $data['userInfo'] = [
            'dashboard' =>"0",
            'role' => [
                "SA","ADMIN","Auditor"
            ],
            "userId"=> "1",
            "userName"=> $username
        ] ;
        if (empty($jwtToken)){
            return  $this->respondWithJson(Result::FAIL([],"登入失败"),$response);
        }
        return $this->respondWithJson(Result::SUCCESS($data,"登入成功"),$response);
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

        $this->validatorByName($request,"register");
        $username = $this->getParamsByName('username');
        $password = $this->getParamsByName('password');
        $exist = $this->userSerice->isExist($username);
        if ($exist) {
            return $this->respondWithJson(Result::FAIL(message: "用户名重复"),$response);
        }

        $id = $this->userSerice->register($username,$password);
        if (empty($id)){
            return $this->respondWithJson(Result::FAIL(message: "注册失败"),$response);
        }
        return $this->respondWithJson(Result::SUCCESS(message: "注册成功"),$response);
    }

    public function logout(Request $request, Response $response, array $args) :Response
    {
//        $jwtToken = $request->getHeader('Authorization');
         $jwtToken = $request->getCookieParams()['token'];
        // 清除缓存
        if (!empty($jwtToken)){
            $this->userSerice->logout($jwtToken);
        }
        return $this->respondWithJson(Result::SUCCESS(message: "注销成功"),$response);

    }

}
