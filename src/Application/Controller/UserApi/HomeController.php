<?php
/**
 * User: 无畏泰坦
 * Date: 2021.12.28 16:58
 * Describe
 */

namespace Application\Controller\UserApi;

use Application\Controller\BaseController;
use Application\Domain\Product\Product;
use Application\Domain\Response\Result;
use Application\Domain\Settings\ValidatorRuleInterface;
use Application\Service\ProductServiceInterface;
use Application\Service\TokenServiceInterface;
use JetBrains\PhpStorm\Pure;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

/**
 * Created on 2021.12.28 16:58
 * Created by 无畏泰坦
 * Describe 首页控制器
 */
class HomeController extends BaseController
{

    private $productService;


    /**
     * @param LoggerInterface $logger
     * @param ValidatorRuleInterface $rule 校验规则
     * @param ProductServiceInterface $productService
     */
    public function __construct(LoggerInterface $logger, ValidatorRuleInterface $rule, TokenServiceInterface $tokenService, ProductServiceInterface $productService)
    {
        parent::__construct($logger, $rule, $tokenService);
        $this->productService = $productService;
        $this->class = self::class;
    }


    /**
     * User: 无畏泰坦
     * Date: 2021.12.28 17:00
     * Describe 获取首页的轮播商品信息
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws JsonException
     */
    public function getBanner(Request $request, Response $response, array $args) :Response
    {
        $res =  $this->productService->getBannerProduct();
        return $this->respondWithJson(Result::SUCCESS($res), $response);
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.29 14:36
     * Describe 获取热门商品
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws JsonException
     */
    public function getHotProduct(Request $request, Response $response, array $args):Response
    {

        $res =  $this->productService->getHotProduct();
        return $this->respondWithJson(Result::SUCCESS($res), $response);
    }
}
