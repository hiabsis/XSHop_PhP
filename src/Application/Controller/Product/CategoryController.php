<?php

namespace Application\Controller\Product;

use Application\Controller\BaseController;
use Application\Domain\Product\Category;
use Application\Domain\Product\ProductInfo;
use Application\Domain\Product\ProductRelatedResource;
use Application\Domain\Response\Result;
use Application\Domain\Settings\ValidatorRuleInterface;
use Application\Helper\ValidatorHelper;
use Application\Service\CategoryServiceInterface;
use Application\Service\TokenServiceInterface;
use JsonException;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;
use function DI\string;
use function DI\value;

class CategoryController extends BaseController
{

    private $categoryService;

    public function __construct(LoggerInterface $logger, ValidatorRuleInterface $rule, TokenServiceInterface $tokenService,CategoryServiceInterface $categoryService)
    {
        parent::__construct($logger, $rule, $tokenService);
        $this->categoryService = $categoryService;
        $this->class = self::class;
    }


    /**
     * 保存商品分类
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws JsonException
     */
    public function saveCategory(Request $request, Response $response, $args): Response
    {
        $this->hasAllRequiredParams($request,"saveCategory");
        $this->validator($request);
        $category = $this->getParamsByClazz(Category::class);
        $resources = $this->getParamsByNameAndClazz('resources', ProductRelatedResource::class);
        $res =  $this->categoryService->saveCategory($category,$resources);
        return  $this->respondWithJson(Result::SUCCESS($res),$response);
    }


    /**
     * @throws JsonException
     */
    public function removeCategory(Request $request, Response $response): Response
    {
        $this->hasAllRequiredParams($request,"removeCategory");
        $id  = $this->getParamsByName('id');
        $res =  $this->categoryService->deleteCategoryById($id);
        return  $this->respondWithJson(Result::SUCCESS($res),$response);

    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.16 10:43
     * Describe 查询商品分类接
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws JsonException
     */
    public function listCategory(Request $request, Response $response, $args)
    {

        $this->hasAllRequiredParams($request,'listCategory');

        $queryCondition = [];
        if (!empty( $this->getParamsByName('parentId'))){
            $queryCondition['parentId'] = $this->getParamsByName('parentId');
        }
        if (!empty( $this->getParamsByName('categoryName'))){
            $queryCondition['categoryName'] = $this->getParamsByName('categoryName');
        }
        $res = $this->categoryService->listCategory($queryCondition);
        return  $this->respondWithJson(Result::SUCCESS($res),$response);
    }

    /**
     * 获取 商品分类树形结构
     * 说明: 1.当前仅仅支持删除单个条记录
     * @URL get /shop/product/category/{id}
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws JsonException
     */
    public function treeCategory(Request $request, Response $response, $args):Response
    {
        $this->hasAllRequiredParams($request,"treeCategory");
        $queryCondition = [];
        if (!empty( $this->getParamsByName('parentId'))){
            $queryCondition['parentId'] = $this->getParamsByName('parentId');
        }
        if (!empty( $this->getParamsByName('categoryName'))){
            $queryCondition['categoryName'] = $this->getParamsByName('categoryName');
        }
        if( empty($this->getParamsByName('size'))){
            $queryCondition['size'] = $this->getParamsByName('size') ?? -1;
        }
        if( empty($this->getParamsByName('page'))){
            $queryCondition['page'] =$this->getParamsByName('page') ?? -1;
        }
        $res = $this->categoryService->listCategoryByTree(queryCondition: $queryCondition);
        return  $this->respondWithJson(Result::SUCCESS($res),$response);
    }

    /**
     * 更新商品分类
     *
     * @URL PUT /shop/product/category/
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws JsonException
     */
    public function putCategory(Request $request, Response $response, $args):Response
    {
        $this->hasAllRequiredParams($request,"putCategory");
        $this->validator($request);
        $category = $this->getParamsByClazz(Category::class);
        $resources = $this->getParamsByNameAndClazz('resources', ProductRelatedResource::class);
        $res = $this->categoryService->updateCategory($category,$resources);
        if ($res){
            return  $this->respondWithJson(Result::SUCCESS($res),$response);
        }else{
            return  $this->respondWithJson(Result::FAIL($res),$response);
        }

    }


    /**
     * User: 无畏泰坦
     * Date: 2021.12.23 11:44
     * Describe 获取首页展示的商品分类的目录
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response {data}
     * @throws JsonException
     */
    public function getIndexCategory(Request $request, Response $response, array $args):Response
    {
        $res = $this->categoryService->getIndexCategory();
        return  $this->respondWithJson(Result::SUCCESS($res),$response);
    }

    protected function validator(Request $request, array $rules = null)
    {

        $resources = $this->getParamsByName('resources');
        $rule = [
            'resourceId' => 'integer|length_max:11',
            'resourceType' => 'integer|length_max:2|in:1,2,3,4,5'
        ];
        if (!empty($resources)){
            foreach ($resources as $resource) {
                ValidatorHelper::validator($resource, $rule);
            }
        }
    }

}
