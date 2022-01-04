<?php

namespace Application\Service\System;

use Application\Constant\SystemConstants;
use Application\Domain\System\Resource;
use Application\Helper\UploadHelper;
use \Application\Model\ResourceModelInterfaceInterface;
use \Application\Service\ResourceServiceInterface;

class ResourceService implements ResourceServiceInterface
{
    /**
     * @var ResourceModelInterfaceInterface
     */
    private $resourceModel;

    public function __construct(ResourceModelInterfaceInterface $resourceModel)
    {
        $this->resourceModel = $resourceModel;
    }

    public function saveResource(Resource $resource): array
    {
        $resourceId = $this->resourceModel->saveResource($resource);
        $imgAccessPath = UploadHelper::getFileAccessPath($resource->url);
        return [
            'resourceId' => $resourceId,
            'resourceAccessPath' => $imgAccessPath
        ];
    }


    public function getFileAccessPath(array $queryCondition): array
    {
      $accessPaths = [];
        $resources = $this->resourceModel->findProductResourceByProductIdAndType($queryCondition['id'], $queryCondition['type']);
        /*** @var $resource Resource */
        foreach ($resources as $resource){
            $accessPaths[] = UploadHelper::getFileAccessPath($resource->url);
        }
        return $accessPaths;
    }

}
