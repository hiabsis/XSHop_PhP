<?php
/**
 * Role: 无畏泰坦
 * Date: 2022.01.05 18:13
 * Describe
 */

namespace Application\Controller\AdminApi;

use Application\Controller\BaseController;
use Application\Domain\Response\Result;
use Application\Domain\Settings\ValidatorRuleInterface;
use Application\Service\RoleServiceInterface;
use Application\Service\TokenServiceInterface;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;


/**
 * Created on 2022.01.05 18:13
 * Created by 无畏泰坦
 * Describe
 */
class RoleController extends BaseController
{
    /**
     * @var RoleServiceInterface
     */
    private  $RoleService;
    public function __construct(LoggerInterface $logger, ValidatorRuleInterface $rule, TokenServiceInterface $tokenService,RoleServiceInterface $RoleService)
    {
        parent::__construct($logger, $rule, $tokenService);
        $this->RoleService = $RoleService;
        $this->class = self::class;
    }

    /**
     * Role: 无畏泰坦
     * Date: 2022.01.06 10:26
     * Describe 加载目录
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws JsonException
     */
    public function loadRoleByPage(Request $request, Response $response, array $args) :Response{
        $this->hasAllRequiredParams($request,'loadRoleByPage');
        $query = $this->getQueryRoleCondition();
        $query[] =  number_format($this->getParamsByName('page')??-1);
        $query[] =  number_format($this->getParamsByName('size')??-1);
        $res = $this->RoleService->listRoleByPage($query);
        return $this->respondWithJson(Result::SUCCESS($res),$response);
    }

    /**
     * Role: 无畏泰坦
     * Date: 2022.01.06 16:44
     * Describe 查询菜单
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws JsonException
     */
    public function searchRoleByPage(Request $request, Response $response, array $args) :Response{
        $this->hasAllRequiredParams($request,'searchRoleByPage');
        $query = $this->getSearchRole();
        $query['page'] =  number_format($this->getParamsByName('page')??-1);
        $query['size'] =  number_format($this->getParamsByName('size')??-1);
        $res = $this->RoleService->listRoleByPage($query);
        return $this->respondWithJson(Result::SUCCESS($res),$response);
    }

    /**
     * Role: 无畏泰坦
     * Date: 2022.01.06 10:26
     * Describe 保存菜单
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws JsonException
     */
    public function saveRole(Request $request, Response $response, array $args) :Response{
        $this->hasAllRequiredParams($request ,'saveRole');
        $Role = $this->getQueryRoleCondition();
        $this->RoleService->saveRole($Role);
        return $this->respondWithJson(Result::SUCCESS(),$response);
    }

    /**
     * Role: 无畏泰坦
     * Date: 2022.01.06 10:27
     * Describe 更新菜单
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws JsonException
     */
    public function updateRole(Request $request, Response $response, array $args) :Response{
        $this->hasAllRequiredParams($request ,'updateRole');
        $updateRole = $this->getQueryRoleCondition();
        $RoleId = $this->getParamsByName('id');
        $this->RoleService->updateRole($updateRole,$RoleId);
        return $this->respondWithJson(Result::SUCCESS(),$response);
    }

    /**
     * Role: 无畏泰坦
     * Date: 2022.01.06 10:27
     * Describe 删除菜单
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws JsonException
     */
    public function removeRole(Request $request, Response $response, array $args) :Response{
        $this->hasAllRequiredParams($request ,'removeRole');
        $ids = $this->getParamsByName('ids');
        $this->RoleService->removeRole($ids);
        return $this->respondWithJson(Result::SUCCESS(),$response);
    }

    /**
     * Role: 无畏泰坦
     * Date: 2022.01.06 11:04
     * Describe 获取参数
     * @return array
     */
    protected function getQueryRoleCondition(): array
    {
        $Role = [];
        if ($this->getParamsByName('name')!== null){
            $Role['name'] = $this->getParamsByName('name');
        }

        return $Role;
    }
    /**
     * Role: 无畏泰坦
     * Date: 2022.01.06 11:04
     * Describe 获取参数
     * @return array
     */
    protected function getSearchRole(): array
    {
        $Role = [];
        if ($this->getParamsByName('data')!== null){
            $Role['name'] = $this->getParamsByName('data');
        }
        return $Role;
    }
}
