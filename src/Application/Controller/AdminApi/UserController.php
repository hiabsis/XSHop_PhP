<?php
/**
 * User: 无畏泰坦
 * Date: 2022.01.05 18:13
 * Describe
 */

namespace Application\Controller\AdminApi;

use Application\Controller\BaseController;
use Application\Domain\Response\Result;
use Application\Domain\Settings\ValidatorRuleInterface;
use Application\Helper\JWTHelper;
use Application\Service\UserServiceInterface;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;


/**
 * Created on 2022.01.05 18:13
 * Created by 无畏泰坦
 * Describe
 */
class UserController extends BaseController
{
    /**
     * @var UserServiceInterface
     */
    private  $UserService;
    public function __construct(LoggerInterface $logger, ValidatorRuleInterface $rule,UserServiceInterface $UserService)
    {
        parent::__construct($logger, $rule);
        $this->UserService = $UserService;
        $this->class = self::class;
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.06 10:26
     * Describe 加载目录
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws JsonException
     */
    public function loadUserByPage(Request $request, Response $response, array $args) :Response{
        $this->validatorByName($request,'loadUserByPage');
        $query = $this->getQueryUserContition();
        $query[] =  number_format($this->getParamsByName('page')??-1);
        $query[] =  number_format($this->getParamsByName('size')??-1);
        $res = $this->UserService->listUserByPage($query);
        return $this->respondWithJson(Result::SUCCESS($res),$response);
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.06 16:44
     * Describe 查询菜单
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws JsonException
     */
    public function searchUserByPage(Request $request, Response $response, array $args) :Response{
        $this->validatorByName($request,'searchUserByPage');
        $query = $this->getSearchUser();
        $query['page'] =  number_format($this->getParamsByName('page')??-1);
        $query['size'] =  number_format($this->getParamsByName('size')??-1);
        $res = $this->UserService->listUserByPage($query);
        return $this->respondWithJson(Result::SUCCESS($res),$response);
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.06 10:26
     * Describe 保存菜单
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws JsonException
     */
    public function saveUser(Request $request, Response $response, array $args) :Response{
        $this->validatorByName($request ,'saveUser');
        $user = $this->getQueryUserContition();
        $this->UserService->saveUser($user);
        return $this->respondWithJson(Result::SUCCESS(),$response);
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.06 10:27
     * Describe 更新菜单
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws JsonException
     */
    public function updateUser(Request $request, Response $response, array $args) :Response{
        $this->validatorByName($request ,'updateUser');
        $updateUser = $this->getQueryUserContition();
        $UserId = $this->getParamsByName('id');
        $this->UserService->updateUser($updateUser,$UserId);
        return $this->respondWithJson(Result::SUCCESS(),$response);
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.06 10:27
     * Describe 删除菜单
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws JsonException
     */
    public function removeUser(Request $request, Response $response, array $args) :Response{
        $this->validatorByName($request ,'removeUser');
        $ids = $this->getParamsByName('ids');
        $this->UserService->removeUser($ids);
        return $this->respondWithJson(Result::SUCCESS(),$response);
    }

    /**
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.14 10:26
     * @description：${description}
     * @modified By：
     * @version:     1.0
     * @throws JsonException
     */
    public function getUserMenuTree(Request $request, Response $response, array $args) :Response{
        $jwtToken = $request->getCookieParams()['token'];
        if (empty($jwtToken)){
            $jwtToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ0b2tlbiI6IjRlNDZjNTMyLTQ1MGYtMzNjYy0yNDQyLTcwMjAyOTM0ZGQ2ZiIsInVzZXJJbmZvIjp7InVzZXJuYW1lIjoiYWRtaW4iLCJpZCI6NH19.UbrRL54asqOIZprOCtIQiQbQ0gy7L7UQmdIvfR6uXQs";
        }
        $payload = JWTHelper::decode($jwtToken);
        if (empty($payload)){
            return $this->respondWithJson(Result::FAIL(message: '获取失败'),$response);
        }

        $tree = $this->UserService->getMenusTreeByCurrentUserId(($payload->userInfo->id));
        return $this->respondWithJson(Result::SUCCESS($tree),$response);
    }
    /**
     * User: 无畏泰坦
     * Date: 2022.01.06 11:04
     * Describe 获取参数
     * @return array
     */
    protected function getQueryUserContition(): array
    {
        $user = [];
        if ($this->getParamsByName('username')!== null){
            $user['username'] = $this->getParamsByName('username');
        }

        return $user;
    }
    /**
     * User: 无畏泰坦
     * Date: 2022.01.06 11:04
     * Describe 获取参数
     * @return array
     */
    protected function getSearchUser(): array
    {
        $user = [];
        if ($this->getParamsByName('data')!== null){
            $user['username'] = $this->getParamsByName('data');
        }
        return $user;
    }


}
