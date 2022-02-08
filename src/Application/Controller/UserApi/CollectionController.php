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
use Application\Service\CollectionServiceInterface;
use Application\Service\TokenServiceInterface;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

class CollectionController extends BaseController
{
    private   $collectionService;
    public function __construct(  CollectionServiceInterface $collectionService,LoggerInterface $logger, ValidatorRuleInterface $rule, TokenServiceInterface $tokenService)
    {
        parent::__construct($logger, $rule, $tokenService);
        $this->collectionService = $collectionService;
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
    public function saveCollection(Request $request, Response $response, array $args) :Response
    {
        $this->hasAllRequiredParams($request,"saveCollection");
        $collect = $this->getCollection();
        $this->collectionService->saveCollect($collect);
        return   $this->respondWithJson(Result::SUCCESS(), $response);
    }
    public function listCollection(Request $request, Response $response, array $args) :Response
    {
        $this->hasAllRequiredParams($request,"listCollection");
        $query = $this->getCollection();
        $query['page'] =  number_format($this->getParamsByName('page')??-1);
        $query['size'] =  number_format($this->getParamsByName('size')??-1);
        $collects = $this->collectionService->listCollectByPage($query);
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
    public function removeCollection(Request $request, Response $response, array $args) :Response
    {
        $this->hasAllRequiredParams($request,"removeCollection");
        $collectId = $this->getParamsByName('collect_id');
        $user = $this->getLoginUserInfo();
        $userId = (int)$user['user_id'];
        $collects = $this->collectionService->removeCollect($collectId,$userId);
        return   $this->respondWithJson(Result::SUCCESS($collects), $response);
    }

    public function getCollection():array
    {
        $collect = [];
        if ($this->getParamsByName('product_id')!== null){
            $collect['product_id'] = $this->getParamsByName('product_id');
        }
        if ($this->getParamsByName('user_id')!== null){
            $collect['user_id'] = $this->getParamsByName('user_id');
            $user = $this->getLoginUserInfo();
            if (($collect['user_id'] !==  $user['user_id'])){
                throw  new CommonException(errorInfo: ErrorEnum::$ERROR_20003);
            }
            $collect['user_id'] = (int) $collect['user_id'] ;
        }
        return  $collect;

    }
}