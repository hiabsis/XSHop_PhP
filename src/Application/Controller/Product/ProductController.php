<?php

namespace Application\Controller\Product;

use Application\Controller\BaseController;

use Application\Domain\Product\Category;
use Application\Domain\Product\Product;
use Application\Domain\Product\ProductInfo;
use Application\Domain\Product\ProductRelatedResource;
use Application\Domain\Response\Result;
use Application\Domain\Settings\ValidatorRuleInterface;
use Application\Domain\System\Resource;
use Application\Helper\ClassHelper;
use Application\Helper\ValidatorHelper;
use Application\Service\CategoryServiceInterface;
use Application\Service\ProductServiceInterface;
use FangStarNet\PHPValidator\Validator;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

class ProductController extends BaseController
{
    private $productService;


    /**
     * @param LoggerInterface $logger
     * @param ValidatorRuleInterface $rule 校验规则
     * @param ProductServiceInterface $productService
     */
    public function __construct(LoggerInterface $logger, ValidatorRuleInterface $rule, ProductServiceInterface $productService)
    {
        parent::__construct($logger, $rule);
        $this->productService = $productService;
        $this->class = self::class;
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.17 13:09
     * Describe 删除商品
     * @param $request
     * @param $response
     * @param $args
     */
    public function removeProduct(Request $request, Response $response, array $args):Response
    {
        $this->validatorByName($request, 'removeProduct');
        $id = $this->getParamsByName('id');
        $this->productService->removeProductById($id);
        return $this->respondWithJson(Result::SUCCESS(), $response);
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.22 14:57
     * Describe  更新商品
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws JsonException
     */
    public function putProduct(Request $request, Response $response, array $args):Response
    {
        $this->validatorByName($request, 'putProduct');
        $this->validator($request);
        // 获取参数
        $product = $this->getParamsByClazz(Product::class);
        $resources = $this->getParamsByNameAndClazz('resources', ProductRelatedResource::class)??[];
        $productInfo = $this->getParamsByNameAndClazz('productInfo', ProductInfo::class)??[];
        // 保存商品
        $this->productService->updateProductDetail($product, $resources, $productInfo);
        return $this->respondWithJson(Result::SUCCESS(), $response);
    }


    /**
     * User: 无畏泰坦
     * Date: 2021.12.17 11:08
     * Describe 获取商品详情
     * @param $request
     * @param $response
     * @param $args
     * @return Response
     * @throws JsonException
     */
    public function detailProduct(Request $request, Response $response, array $args):Response
    {
        //参数校验
        $this->validatorByName($request, 'detailProduct');
        // 获取参数
        $id = $this->getParamsByName('id');
        // 商品详情
        $res = $this->productService->getProductDetail($id);
        // 返回数据
        return $this->respondWithJson(Result::SUCCESS($res), $response);
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.17 10:55
     * Describe 添加商品
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws JsonException
     */
    public function saveProduct(Request $request, Response $response, array $args):Response
    {

        // 预设校验
        $this->validatorByName($request, 'saveProduct');
        // 自定义校验
        $this->validator($request);
        // 获取参数
        $product = $this->getParamsByClazz(Product::class);
        $resources = $this->getParamsByNameAndClazz('resources', ProductRelatedResource::class);
        $productInfo = $this->getParamsByNameAndClazz('productInfo', ProductInfo::class);
        // 保存商品
        $res = $this->productService->saveProductDetail($product, $resources, $productInfo);
        return $this->respondWithJson(Result::SUCCESS($res), $response);
    }

    /**
     * 获取最新商品
     */
    public function newestProduct($request, $response, $args)
    {
        $this->validatorByName($request, 'newestProduct');
    }

    /**
     * 获取热门商品
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public function hotProduct($request, $response, $args)
    {
        $this->validatorByName($request, 'hotProduct');
    }

    protected function validator(Request $request, array $rules = null)
    {

        $resources = $this->getParamsByName('resources');
        $rule = [
            'resourceId' => 'integer|length_max:11',
            'type' => 'integer|length_max:2|in:1,2,3,4,5'
        ];
        if (!empty($resources)){
            foreach ($resources as $resource) {
                ValidatorHelper::validator($resource, $rule);
            }
        }

        $productInfo = $this->getParamsByName('productInfo');
        if (!empty($productInfo)){
            foreach ($productInfo as $item) {
                ValidatorHelper::validator($item, ValidatorHelper::getRules(ProductInfo::class));
            }
        }


    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.22 14:58
     * Describe 获取商品轮播商品
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response 请求成功 [ code: 200 ,msg:success,data:[ [productId : id , imgUrl :imgAccessPatch],...] ]
     *
     * @throws JsonException
     */
    public function listProductCarousel(Request $request, Response $response, array $args):Response
    {
        $res = $this->productService->listProductCarousel();

        return $this->respondWithJson(Result::SUCCESS($res), $response);
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.22 17:04
     * Describe 获取商品列表
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws JsonException
     */
    public function listProduct(Request $request, Response $response, array $args):Response
    {
        // 预设校验
        $this->validatorByName($request, 'listProduct');
        $queryCondition = [];
        $limit = [];
        if (!empty( $this->getParamsByName('productName'))){
            $queryCondition['parentId'] = $this->getParamsByName('productName');
        }
        if (!empty( $this->getParamsByName('productStatus'))){
            $queryCondition['categoryName'] = $this->getParamsByName('productStatus');
        }
        $limit['page'] = $this->getParamsByName('page')??1;
        $limit['size'] = $this->getParamsByName('size')??10;
        $res = $this->productService->listProduct($queryCondition,$limit);
        return $this->respondWithJson(Result::SUCCESS($res), $response);
    }




}
