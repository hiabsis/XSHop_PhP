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
use Application\Service\MenuServiceInterface;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;


/**
 * Created on 2022.01.05 18:13
 * Created by 无畏泰坦
 * Describe
 */
class MenuController extends BaseController
{
    /**
     * @var MenuServiceInterface
     */
    private  $menuService;
    public function __construct(LoggerInterface $logger, ValidatorRuleInterface $rule,MenuServiceInterface $menuService)
    {
        parent::__construct($logger, $rule);
        $this->menuService = $menuService;
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
    public function loadMenuByPage(Request $request, Response $response, array $args) :Response{
        $this->validatorByName($request,'loadMenuByPage');
        $page =  number_format($this->getParamsByName('page')??-1);
        $size =  number_format($this->getParamsByName('size')??-1);
        $res = $this->menuService->listMenuByPage($page,$size);
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
    public function searchMenuByPage(Request $request, Response $response, array $args) :Response{
        $this->validatorByName($request,'searchMenuByPage');
        $query = $this->getSearchMenu();
        $query['page'] =  number_format($this->getParamsByName('page')??-1);
        $query['size'] =  number_format($this->getParamsByName('size')??-1);
        $res = $this->menuService->searchMenuByPage($query);
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
    public function saveMenu(Request $request, Response $response, array $args) :Response{
        $this->validatorByName($request ,'saveMenu');
        $menu = $this->getMenu();
        $this->menuService->saveMenu($menu);
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
    public function updateMenu(Request $request, Response $response, array $args) :Response{
        $this->validatorByName($request ,'updateMenu');
        $updateMenu = $this->getMenu();
        $menuId = $this->getParamsByName('id');
        $this->menuService->updateMenu($updateMenu,$menuId);
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
    public function removeMenu(Request $request, Response $response, array $args) :Response{
        $this->validatorByName($request ,'removeMenu');
        $ids = $this->getParamsByName('ids');
        $this->menuService->removeMenu($ids);
        return $this->respondWithJson(Result::SUCCESS(),$response);
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.06 11:04
     * Describe 获取参数
     * @return array
     */
    protected function getMenu(): array
    {
        $menu = [];
        if ($this->getParamsByName('path')!== null){
            $menu['path'] = $this->getParamsByName('path');
        }
        if ($this->getParamsByName('name')!== null){
            $menu['name'] = $this->getParamsByName('name');
        }
        if ($this->getParamsByName('component')!== null){
            $menu['component'] = $this->getParamsByName('component');
        }
        if ($this->getParamsByName('parent_id')!== null){
            $menu[''] = $this->getParamsByName('parent_id');
        }
        if ($this->getParamsByName('name_zh')!== null){
            $menu['name_zh'] = $this->getParamsByName('name_zh');
        }

        return $menu;
    }
    /**
     * User: 无畏泰坦
     * Date: 2022.01.06 11:04
     * Describe 获取参数
     * @return array
     */
    protected function getSearchMenu(): array
    {
        $menu = [];
        if ($this->getParamsByName('data')!== null){
            $menu['path'] = $this->getParamsByName('data');
            $menu['name'] = $this->getParamsByName('data');
            $menu['component'] = $this->getParamsByName('data');
            $menu['name_zh'] = $this->getParamsByName('data');
        }
        return $menu;
    }
}
