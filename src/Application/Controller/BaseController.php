<?php

namespace Application\Controller;

use App\Application\Actions\ActionPayload;
use Application\Domain\Response\Result;
use Application\Domain\Settings\ValidatorRuleInterface;
use Application\Helper\ClassHelper;
use Application\Helper\ValidatorHelper;
use JsonException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteContext;


abstract class BaseController
{



    protected  $rules;
    protected  $logger;
    protected $params;
    protected $class;
    public function __construct(LoggerInterface $logger,ValidatorRuleInterface $rule)
    {
        $this->logger = $logger;
        $this->rules = $rule;
    }


    /**
     * 处理结果 JSON
     * @param array $result [data => [] ,message=> "",status => 200]
     * @param Response $response
     * @return Response
     * @throws JsonException
     */
    protected function respondWithJson(array $result = null, Response  $response = null): Response
    {
        $result['status']  = 200;
        $payload = json_encode($result, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }


    /**
     * User: 无畏泰坦
     * Date: 2021.12.15 16:11
     * Describe 通过预设的校验规则校验参数
     * @param Request $request
     * @param string $name  校验规则名称
     */
    protected function validatorByName(Request $request,string $name){
        $data = [];
        $name = $this->class.":".$name;
        if (!empty($request->getUploadedFiles()) ){
            $data = array_merge($data,$request->getUploadedFiles()) ;
        }
        if (!empty( $request->getQueryParams())){
            $data = array_merge($request->getQueryParams() ,$data);
        }
        if (!empty($request->getParsedBody())){
            $data = array_merge($request->getParsedBody(),$data) ;
        }
        if (!empty($request->getAttributes())){
            $data = array_merge($request->getAttributes(),$data) ;
        }
        $rule = $this->rules->getValidatorRule($name);

        ValidatorHelper::validator($data,$rule);
        $this->params = $data;
    }
    protected function validator(Request $request,array $rules = null){

    }

    protected function getParamsByClazz($clazz):object
    {
        return ClassHelper::newInstance( $this->params,$clazz);
    }
    protected function getParamsByName($name)
    {
        if (array_key_exists($name,$this->params)){
            return $this->params[$name];
        }
        return null;
    }

    protected function getParamsByNameAndClazz($name,$clazz){
        if (array_key_exists($name,$this->params) && is_array($this->params[$name])){
            return ClassHelper::newInstanceArray($this->params[$name],$clazz);
        }
    }

    /**
     * 获取当前系统用户信息
     * @return array
     */
    protected function getCurrentSystemUserInfo():array
    {


        return  [];
    }





}
