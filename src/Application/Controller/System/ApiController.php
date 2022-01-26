<?php
/**
 * @author     ：无畏泰坦
 * @date       ：Created in 2022.01.21 21:32
 * @description：
 * @modified By：
 * @version:     1.0
 */

namespace Application\Controller\System;

use Application\Controller\BaseController;
use Application\Domain\Response\Result;
use Application\Domain\Settings\ValidatorRuleInterface;
use Application\Service\ApiServiceInterface;
use Application\Service\TokenServiceInterface;
use JetBrains\PhpStorm\Pure;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

class ApiController extends BaseController
{
    /**
     * @var ApiServiceInterface
     */
    private $ApiService;
    #[Pure] public function __construct(ApiServiceInterface $apiService, LoggerInterface $logger, ValidatorRuleInterface $rule, TokenServiceInterface $tokenService)
    {
        parent::__construct($logger, $rule, $tokenService);
        $this->ApiService = $apiService;
        $this->class = self::class;
    }

    /**
     * Api: 无畏泰坦
     * Date: 2022.01.06 10:26
     * Describe 加载目录
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws JsonException
     */
    public function loadApiByPage(Request $request, Response $response, array $args) :Response{
        $this->hasAllRequiredParams($request,'loadApiByPage');
        $query = $this->getQueryApiCondition();
        $query[] =  number_format($this->getParamsByName('page')??-1);
        $query[] =  number_format($this->getParamsByName('size')??-1);
        $res = $this->ApiService->listApiByPage($query);
        return $this->respondWithJson(Result::SUCCESS($res),$response);
    }

    /**
     * Api: 无畏泰坦
     * Date: 2022.01.06 16:44
     * Describe 查询菜单
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws JsonException
     */
    public function searchApiByPage(Request $request, Response $response, array $args) :Response{
        $this->hasAllRequiredParams($request,'searchApiByPage');
        $query = $this->getSearchApi();
        $query['page'] =  number_format($this->getParamsByName('page')??-1);
        $query['size'] =  number_format($this->getParamsByName('size')??-1);
        $res = $this->ApiService->listApiByPage($query);
        return $this->respondWithJson(Result::SUCCESS($res),$response);

    }

    /**
     * Api: 无畏泰坦
     * Date: 2022.01.06 10:26
     * Describe 保存菜单
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws JsonException
     */
    public function saveApi(Request $request, Response $response, array $args) :Response{
        $this->hasAllRequiredParams($request ,'saveApi');
        $Api = $this->getQueryApiCondition();
        // 检查路径的唯一性
        $api= $this->ApiService->getApi(query: ['path' => $Api['path']]);
        if (!empty($api)){
            return $this->respondWithJson(Result::FAIL(message:"接口路径重复！！！"),$response);
        }
        $this->ApiService->saveApi($Api);
        return $this->respondWithJson(Result::SUCCESS(),$response);

    }

    /**
     * Api: 无畏泰坦
     * Date: 2022.01.06 10:27
     * Describe 更新菜单
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws JsonException
     */
    public function updateApi(Request $request, Response $response, array $args) :Response{
        $this->hasAllRequiredParams($request ,'updateApi');
        $updateApi = $this->getQueryApiCondition();
        $ApiId = $this->getParamsByName('id');
        $menuIds = $this->getParamsByName('menuIds');
        $this->ApiService->updateApi($updateApi,$ApiId,$menuIds);
        return $this->respondWithJson(Result::SUCCESS(),$response);
    }

    /**
     * Api: 无畏泰坦
     * Date: 2022.01.06 10:27
     * Describe 删除菜单
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws JsonException
     */
    public function removeApi(Request $request, Response $response, array $args) :Response{
        $this->hasAllRequiredParams($request ,'removeApi');
        $ids = $this->getParamsByName('ids');
        $this->ApiService->removeApi($ids);
        return $this->respondWithJson(Result::SUCCESS(),$response);
    }

    /**
     * Api: 无畏泰坦
     * Date: 2022.01.06 11:04
     * Describe 获取参数
     * @return array
     */
    protected function getQueryApiCondition(): array
    {
        $Api = [];
        if ($this->getParamsByName('name')!== null){
            $Api['name'] = $this->getParamsByName('name');
        }
        if ($this->getParamsByName('path')!== null){
            $Api['path'] = $this->getParamsByName('path');
        }
        if ($this->getParamsByName('status')!== null){
            $Api['status'] = $this->getParamsByName('status');
        }
        if ($this->getParamsByName('type')!== null){
            $Api['type'] = $this->getParamsByName('type');
        }
        if ($this->getParamsByName('permission')!== null){
            $Api['permission'] = $this->getParamsByName('permission');
        }

        return $Api;
    }
    /**
     * Api: 无畏泰坦
     * Date: 2022.01.06 11:04
     * Describe 获取参数
     * @return array
     */
    protected function getSearchApi(): array
    {
        $Api = [];
        if ($this->getParamsByName('data')!== null){
            $Api['name'] = $this->getParamsByName('data');
        }
        return $Api;
    }
}