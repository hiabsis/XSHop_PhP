<?php
/**
 * @author     ：无畏泰坦
 * @date       ：Created in 2022.01.27 10:40
 * @description：
 * @modified By：
 * @version:     1.0
 */

namespace Application\Controller\UserApi;

use Application\Constant\ErrorEnum;
use Application\Controller\BaseController;
use Application\Domain\Response\Result;
use Application\Domain\Settings\ValidatorRuleInterface;
use Application\Exception\CommonException;
use Application\Service\CartServiceInterface;
use Application\Service\CollectionServiceInterface;
use Application\Service\TokenServiceInterface;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

class CartController extends BaseController
{
    private   $cartService;
    public function __construct(  CartServiceInterface $cartService,LoggerInterface $logger, ValidatorRuleInterface $rule, TokenServiceInterface $tokenService)
    {
        parent::__construct($logger, $rule, $tokenService);
        $this->cartService = $cartService;
        $this->class = self::class;
    }

    /**
     * @version:     1.0
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.27 10:52
     * @description：用户收藏
     * @modified By：
     * @throws JsonException
     */
    public function saveCart(Request $request, Response $response, array $args) :Response
    {
        $this->hasAllRequiredParams($request,"saveCart");
        $cart = $this->getCart();
        $result = $this->cartService->saveCart($cart);
        return   $this->respondWithJson(Result::SUCCESS(data: $result), $response);
    }

    /**
     * @throws JsonException
     * @version:     1.0
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.27 15:28
     * @description：${description}
     * @modified By：
     */
    public function listCart(Request $request, Response $response, array $args) :Response
    {
        $this->hasAllRequiredParams($request,"listCart");
        $query = $this->getCart();
        $query['page'] =  number_format($this->getParamsByName('page')??-1);
        $query['size'] =  number_format($this->getParamsByName('size')??-1);
        $collects = $this->cartService->listCartByPage($query);
        return   $this->respondWithJson(Result::SUCCESS($collects), $response);
    }

    /**
     * @throws JsonException
     * @version:     1.0
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.27 14:39
     * @description：${description}
     * @modified By：
     */
    public function removeCart(Request $request, Response $response, array $args) :Response
    {
        $this->hasAllRequiredParams($request,"removeCart");
        $cartId =(int) $this->getParamsByName('cart_id');
        $user = $this->getLoginUserInfo();
        $userId = (int)$user['user_id'];
        $collects = $this->cartService->removeCart($cartId,$userId);
        return   $this->respondWithJson(Result::SUCCESS($collects), $response);
    }

    /**
     * @throws JsonException
     * @version:     1.0
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.27 17:32
     * @description：${description}
     * @modified By：
     */
    public function updateCart(Request $request, Response $response, array $args) :Response
    {
        $this->hasAllRequiredParams($request,"updateCart");
        $cart = $this->getCart();
        $cartId = (int) $this->getParamsByName('cart_id');
        $user = $this->getLoginUserInfo();
        $result = $this->cartService->updateCart($cart,$cartId,(int)$user['user_id']);
        return   $this->respondWithJson(Result::SUCCESS(message: $result), $response);
    }
    public function getCart():array
    {
        $cart = [];
        if ($this->getParamsByName('product_id')!== null){
            $cart['product_id'] = $this->getParamsByName('product_id');
        }
        if ($this->getParamsByName('number')!== null){
            $cart['number'] = $this->getParamsByName('number');
        }
        if ($this->getParamsByName('user_id')!== null){
            $cart['user_id'] = $this->getParamsByName('user_id');
            $user = $this->getLoginUserInfo();
            if (($cart['user_id'] !==  $user['user_id'])){
                throw  new CommonException(errorInfo: ErrorEnum::$ERROR_20003);
            }
            $cart['user_id'] = (int) $cart['user_id'] ;
        }
        return  $cart;

    }
}