<?php

namespace Application\Service\System;

use Application\Constant\ErrorEnum;
use Application\Constant\SystemConstants;
use Application\Domain\System\Resource;
use Application\Exception\CommonException;
use Application\Helper\UploadHelper;
use \Application\Model\ResourceModelInterface;
use \Application\Service\ResourceServiceInterface;

class ResourceService implements ResourceServiceInterface
{
    /**
     * @var ResourceModelInterface
     */
    private $resourceModel;

    public function __construct(ResourceModelInterface $resourceModel)
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


    public function getResourceInfo(array $queryCondition): array
    {
        $res = [];
        $resources = $this->resourceModel->findProductResourceByProductIdAndType($queryCondition['id'], $queryCondition['type']);
        /*** @var $resource Resource */
        foreach ($resources as $resource){
            $temp['fileAccessPath'] = UploadHelper::getFileAccessPath($resource->url);
            $temp['resourceId'] =  $resource->id;
            $temp['resourceType'] =  $queryCondition['type'];
            $res[] = $temp;
        }
        return $res;
    }
    public function removeResourceInfo(int $id,string $path ): bool
    {
        $this->resourceModel->getResourceById($id);
        if ($path !== $id['url']){
            throw  new  CommonException(errorInfo: ErrorEnum::$ERROR_30001);
        }
        return  $this->resourceModel->removeResourceByIds([$id]);
    }
}
