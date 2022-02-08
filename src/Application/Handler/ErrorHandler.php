<?php
declare(strict_types=1);

namespace Application\Handler;


use Application\Exception\BaseException;
use Application\Exception\CommonException;
use Application\Exception\HttpErrorStatus;
use Application\Exception\SystemErrorInfo;
use Exception;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;
use Throwable;

class ErrorHandler extends SlimErrorHandler
{

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.20 17:41
     * Describe
     * 本程序用于统一处理系统异常 , 返回给前端错误格式为json的错误信息
     * 异常提示信息 [status => 请求状态码 , code => 异常码, msg => 系统提示错误信息]
     * 基础日志信息 [ code => 异常码, msg => 异常信息 ,position 异常位置 ]
     * 如果$exception是继承了BaseException(自定义的异常):
     *  获取需要记录的日志信息，和异常提示信息
     * 如果$exception是HTTPException或者PDOException或者RedisException
     *  通过$exception的全限定类名在SystemErrorInfo中获取需要记录的日志信息，和异常提示信息，更具不同信息进行提示
     * 设置请求的状态码 $status,默认500
     *  如果是HTTPException,则根据$exception的错误码进行设置
     * 记录日志记录，设置Response
     * @return Response
     * @throws JsonException
     */
    protected function respond(): Response
    {

        $exception = $this->exception;
        $logInfo = json_encode([
            'code' => 100000,
            'position' => $exception->getFile() . ' ' . $exception->getLine(),
            'msg' => $exception->getMessage(),
        ], JSON_THROW_ON_ERROR);
        $errorInfo = [
            'status' => 500,
            'code' => 100000,
            'msg' => '系统异常'
        ];
        $status = 500;
        if ($exception instanceof  CommonException){
            $logInfo = SystemErrorInfo::getErrorLogInfo($exception);
            $logInfo['msg'] = $exception->getMessage();
            $logInfo['position'] = $exception->getFile() . ' ' . $exception->getLine();
            $errorInfo = $exception->getErrorInfo();
        } else if ($exception instanceof BaseException){
            $logInfo = $exception->getLoggerInfo();
            $errorInfo = $exception->getErrorInfo();
        }else if ($exception instanceof HttpException ){
            $status = $exception->getCode();
            $logInfo = SystemErrorInfo::getErrorLogInfo($exception);
            $errorInfo = SystemErrorInfo::getErrorInfo($exception);
            $errorInfo['status'] = $status;
        }else if( $exception instanceof \PDOException ||  $exception instanceof  \RedisException) {
            $logInfo = SystemErrorInfo::getErrorLogInfo($exception);
            $logInfo['msg'] = $exception->getMessage();
            $logInfo['position'] = $exception->getFile() . ' ' . $exception->getLine();
            $errorInfo = SystemErrorInfo::getErrorInfo($exception);
        }

        $this->logger->error(json_encode($errorInfo, JSON_THROW_ON_ERROR|JSON_UNESCAPED_UNICODE));
        $encodedPayload = json_encode($errorInfo, JSON_THROW_ON_ERROR|JSON_UNESCAPED_UNICODE);
        $response = $this->responseFactory->createResponse($status);
        $response->getBody()->write($encodedPayload);

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

}
