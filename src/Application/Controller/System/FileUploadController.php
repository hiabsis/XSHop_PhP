<?php

namespace Application\Controller\System;

use Application\Domain\Response\Result;
use Application\Domain\Settings\ValidatorRuleInterface;
use Application\Domain\System\Resource;
use Application\Helper\ClassHelper;
use \Application\Helper\UploadHelper;
use Application\Service\ResourceServiceInterface;
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
    public function __construct(ResourceServiceInterface $resourceService, LoggerInterface $logger, ValidatorRuleInterface $rule)
    {
        $this->resourceService = $resourceService;
        parent::__construct($logger, $rule);
        $this->class = self::class;
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


}
