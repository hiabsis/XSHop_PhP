<?php


return function (\DI\ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        \Application\Service\CategoryServiceInterface::class   =>   \DI\autowire(\Application\Service\Product\CategoryService::class),
        \Application\Service\ProductServiceInterface::class   =>   \DI\autowire(\Application\Service\Product\ProductService::class),
        \Application\Service\ResourceServiceInterface::class  => \DI\autowire(\Application\Service\System\ResourceService::class),
        \Application\Service\UserServiceInterface::class => \DI\autowire(\Application\Service\System\UserService::class)
    ]);
};
