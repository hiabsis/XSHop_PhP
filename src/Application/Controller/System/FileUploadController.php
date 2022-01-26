<?php

namespace Application\Controller\System;

use Application\Domain\Response\Result;
use Application\Domain\Settings\ValidatorRuleInterface;
use Application\Domain\System\Resource;
use Application\Helper\ClassHelper;
use \Application\Helper\UploadHelper;
use Application\Service\ResourceServiceInterface;
use Application\Service\TokenServiceInterface;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Log\LoggerInterface;

class FileUploadController extends \Application\Controller\BaseController
{
    /**
     * @var ResourceServiceInterface
     */
    private $resourceService;

   public function __construct(LoggerInterface $logger, ValidatorRuleInterface $rule, TokenServiceInterface $tokenService,ResourceServiceInterface $resourceService)
   {
       parent::__construct($logger, $rule, $tokenService);
       $this->resourceService = $resourceService;
   }


    /**
     * 图片上传
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws JsonException
     */
    public function uploadImg(Request $request, Response $response, array $args): Response
     {
        $uploadedFiles = $request->getUploadedFiles();
        $uploadedImg = $uploadedFiles['file'];
        $data = UploadHelper::saveImg($uploadedImg);
        $resource = ClassHelper::newInstance($data,Resource::class);
        $res = $this->resourceService->saveResource($resource);
        return $this->respondWithJson(Result::SUCCESS($res), $response);
    }

    /**
     * User: 无畏泰坦
     * Date: 2021.12.31 13:05
     * Describe 获取图片的访问路径
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws JsonException
     */
    public function getResourceInfo(Request $request, Response $response, array $args):Response
    {
        $this->hasAllRequiredParams($request,"getImgAccessPath");
        $queryCondition = [];
        $queryCondition['id'] = (int)($this->getParamsByName('id'));
        $queryCondition['type'] = (int)$this->getParamsByName('type');
        $res = $this->resourceService->getResourceInfo($queryCondition);
        return $this->respondWithJson(Result::SUCCESS($res), $response);
    }

    /**
     * @throws JsonException
     */
    public function removeResourceInfo(Request $request, Response $response, array $args):Response
    {
        if (empty($this->getParamsByName('id'))){
              return $this->respondWithJson(Result::FAIL(), $response);
        }
        $id = (int)($this->getParamsByName('id'));
        $path = (int)($this->getParamsByName('path'));
        $res = $this->resourceService->removeResourceInfo($id,$path);
        return $this->respondWithJson(Result::SUCCESS($res), $response);
    }



}
