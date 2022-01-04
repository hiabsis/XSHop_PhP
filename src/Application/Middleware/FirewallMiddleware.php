<?php

namespace Application\Middleware;

use Application\Helper\ValidatorHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;

/**
 * 防火墙
 * 拦截sql注入和xss等
 */
class FirewallMiddleware  implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
//        $str = urldecode($_SERVER['REQUEST_URI']) .
//        urldecode(file_get_contents('php://input')) .
//        implode('', getallheaders());
//        if (!empty($_SERVER['HTTP_COOKIE'])){
//            $str .=  urldecode($_SERVER['HTTP_COOKIE']);
//        }
//
//
//        ValidatorHelper::checkSqlInjection($str);
//        ValidatorHelper::hasXSSAttacking($str);
        return $handler->handle($request);
    }
}
