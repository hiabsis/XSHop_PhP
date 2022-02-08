<?php
/**
 * @author     ：无畏泰坦
 * @date       ：Created in 2022.02.08 9:42
 * @description： 订单
 * @modified By：
 * @version:     1.0
 */

namespace Application\Controller\UserApi;

use Application\Constant\ErrorEnum;
use Application\Controller\BaseController;
use Application\Domain\Response\Result;
use Application\Domain\Settings\ValidatorRuleInterface;
use Application\Exception\CommonException;
use Application\Model\OrderModel;
use Application\Service\OrderServiceInterface;
use Application\Service\TokenServiceInterface;
use JetBrains\PhpStorm\Pure;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

class OrderController extends BaseController
{
    private $orderServe;
    #[Pure] public function __construct(OrderServiceInterface $orderService,LoggerInterface $logger, ValidatorRuleInterface $rule, TokenServiceInterface $tokenService)
    {
        parent::__construct($logger, $rule, $tokenService);
        $this->class = self::class;
        $this->orderServe = $orderService;
    }

    /**
     * @version:     1.0
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.27 10:52
     * @description：用户收藏
     * @modified By：
     * @throws JsonException
     */
    public function saveOrder(Request $request, Response $response, array $args) :Response
    {

        $this->hasAllRequiredParams($request,"saveOrder");
        $userId =  $this->getParamsByName('user_id');
        $userInfo = $this->getLoginUserInfo();

        $products = $this->getParamsByName('products');
        $addressId = $this->getParamsByName('address_id');
        $totalScore = $this->getParamsByName('total');
        // 非登入用户违法操作
        if ($userId !== $userInfo['user_id']){
            throw new CommonException(errorInfo: ErrorEnum::$ERROR_20003);
        }
        // 检查用户积分
        if ((int)$userInfo['score'] < $totalScore){
            throw new CommonException(errorInfo: ErrorEnum::$ERROR_40001);
        }
        $this->orderServe->saveOrder($userId,$products,$addressId,$this->getRequestToken($request));
        return   $this->respondWithJson(Result::SUCCESS(message: "001"), $response);
    }


    /**
     * @version:     1.0
     * @author     ：无畏泰坦
     * @date       ：Created in 2022.01.27 10:52
     * @description：用户收藏
     * @modified By：
     * @throws JsonException
     */
    public function pageOrder(Request $request, Response $response, array $args) :Response
    {

        $this->hasAllRequiredParams($request,"pageOrder");
        $userId =  $this->getParamsByName('user_id');
        $userInfo = $this->getLoginUserInfo();
        // 非登入用户违法操作
        if ($userId !== $userInfo['user_id']){
            throw new CommonException(errorInfo: ErrorEnum::$ERROR_20003);
        }
        $orders =  $this->orderServe->listOrderByPage(query: ['user_id'=>$userId]);
        return   $this->respondWithJson(Result::SUCCESS($orders), $response);
    }


}