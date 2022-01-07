<?php
/**
 * User: 无畏泰坦
 * Date: 2022.01.07 10:47
 * Describe
 */

namespace Application\Controller\UserApi;

use Application\Controller\BaseController;
use Application\Domain\Response\Result;
use Application\Domain\Settings\ValidatorRuleInterface;
use Application\Service\UserServiceInterface;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

/**
 * Created on 2022.01.07 10:47
 * Created by 无畏泰坦
 * Describe
 */
class LoginController extends BaseController
{
    /**
     * @var UserServiceInterface
     */
    private $userSerice;
    public function __construct(LoggerInterface $logger, ValidatorRuleInterface $rule,UserServiceInterface $userService)
    {
        parent::__construct($logger, $rule);
        $this->class = self::class;
        $this->userSerice = $userService;
    }

    /**
     * User: 无畏泰坦
     * Date: 2022.01.07 11:41
     * Describe 登入
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws JsonException
     */
    public function login(Request $request, Response $response, array $args) :Response
    {
        $this->validatorByName($request,"login");
        $username = $this->getParamsByName('username');
        $password = $this->getParamsByName('password');
        $user = $this->userSerice->getUser(username: $username,password: $password);
        if (empty($user)){
            return $this->respondWithJson(Result::FAIL(),$response);
        }
        return $this->respondWithJson(Result::SUCCESS($user),$response);
    }
}
