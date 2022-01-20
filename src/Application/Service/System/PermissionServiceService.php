<?php
/**
 * @author     ：无畏泰坦
 * @date       ：Created in 2022.01.19 17:10
 * @description：
 * @modified By：
 * @version:     1.0
 */

namespace Application\Service\System;

use Application\Constant\ErrorEnum;
use Application\Constant\SystemConstants;
use Application\Exception\CommonException;
use Application\Model\ApiModelInterface;
use Application\Model\Impl\BaseModel;
use Application\Service\BaseService;
use Application\Service\PermissionServiceServiceInterface;
use Application\Service\TokenServiceInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpUnauthorizedException;

class PermissionServiceService extends BaseService implements PermissionServiceServiceInterface
{
    private $apiModel ;
    private $tokenService;
    public function __construct(ApiModelInterface $apiModel,TokenServiceInterface $tokenService)
    {
        $this->apiModel =  $apiModel;
        $this->tokenService = $tokenService;
    }




}