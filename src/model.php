<?php




return function (\DI\ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        \Application\Model\CategoryModelInterfaceInterface::class   => \DI\autowire(\Application\Model\Impl\CategoryModel::class),
        \Application\Model\ProductModelInterfaceInterFace::class => \DI\autowire(\Application\Model\Impl\ProductModel::class),
        \Application\Model\ProductInfoModelInterFaceInterface::class => \DI\autowire(\Application\Model\Impl\ProductInfoModel::class),
        \Application\Model\ResourceModelInterfaceInterface::class => \DI\autowire(\Application\Model\Impl\ResourceModel::class),
        \Application\Model\ProductRelatedResourceModelInterface::class =>  \DI\autowire(\Application\Model\Impl\ProductRelatedResourceModel::class),
        \Application\Model\UserModelInterfaceInterface::class => \DI\autowire(\Application\Model\Impl\UserModel::class)
    ]);
};
