<?php
/**
 * User: 无畏泰坦
 * Date: 2021.12.15 15:03
 * Describe
 */

namespace Application\Controller\System;

use Application\Controller\BaseController;
use Application\Domain\Response\Result;
use Application\Domain\Settings\ValidatorRuleInterface;
use Application\Domain\System\User;
use Application\Service\UserServiceInterface;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

/**
 * Created on 2021.12.15 15:03
 * Created by 无畏泰坦
 * Describe
 */
class LoginController extends BaseController
{

    private $userService;
    public function __construct(LoggerInterface $logger, ValidatorRuleInterface $rule, UserServiceInterface $userService)
    {
        parent::__construct($logger, $rule);
        $this->userService = $userService;

    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.15 15:04
     * Describe 登入
     * @throws JsonException
     */
    public function login(Request $request, Response $response, array $args)
    {
        $this->validatorByName($request, self::class . ":login");
        $user = $this->getParamsByClazz(User::class);
        $res = $this->userService->login($user);
        return $this->respondWithJson(Result::SUCCESS($res),$response);
    }
}
