<?php




return function (\DI\ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        \Application\Model\CategoryModelInterface::class   => \DI\autowire(\Application\Model\Impl\CategoryModel::class),
        \Application\Model\ProductModelInterface::class => \DI\autowire(\Application\Model\Impl\ProductModel::class),
        \Application\Model\ProductInfoModelInterFace::class => \DI\autowire(\Application\Model\Impl\ProductInfoModel::class),
        \Application\Model\ResourceModelInterface::class => \DI\autowire(\Application\Model\Impl\ResourceModel::class),
        \Application\Model\ProductRelatedResourceModelInterface::class =>  \DI\autowire(\Application\Model\Impl\ProductRelatedResourceModel::class),
        \Application\Model\UserModelInterface::class => \DI\autowire(\Application\Model\Impl\UserModel::class),
        \Application\Model\RoleModelInterface::class => \DI\autowire(\Application\Model\Impl\RoleModel::class),
        \Application\Model\MenuModelInterface::class => \DI\autowire(\Application\Model\Impl\MemuModel::class),
        \Application\Model\RoleMenuModelInterface::class =>  \DI\autowire(\Application\Model\Impl\RoleMenuModel::class),
        \Application\Model\UserRoleModelInterface::class =>  \DI\autowire(\Application\Model\Impl\UserRoleModel::class),
        \Application\Model\ApiMenuModelInterface::class =>  \DI\autowire(\Application\Model\Impl\ApiMenuModel::class),
        \Application\Model\ApiModelInterface::class =>  \DI\autowire(\Application\Model\Impl\ApiModel::class),
        \Application\Model\CollectionModelInterface::class =>  \DI\autowire(\Application\Model\Impl\CollectionModel::class),
        \Application\Model\CartModelInterface::class =>  \DI\autowire(\Application\Model\Impl\CartModel::class),
        \Application\Model\OrderModel::class =>  \DI\autowire(\Application\Model\Impl\OrderModel::class),
       ]);
};
