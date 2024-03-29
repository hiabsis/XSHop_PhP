<?php


return function (\DI\ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        \Application\Service\CategoryServiceInterface::class   =>   \DI\autowire(\Application\Service\Product\CategoryService::class),
        \Application\Service\ProductServiceInterface::class   =>   \DI\autowire(\Application\Service\Product\ProductService::class),
        \Application\Service\ResourceServiceInterface::class  => \DI\autowire(\Application\Service\System\ResourceService::class),
        \Application\Service\UserServiceInterface::class => \DI\autowire(\Application\Service\System\UserService::class),
        \Application\Service\RoleServiceInterface::class => \DI\autowire(\Application\Service\System\RoleService::class),
        \Application\Service\MenuServiceInterface::class => \DI\autowire(\Application\Service\System\MenuService::class),
        \Application\Service\TokenServiceInterface::class => \DI\autowire(\Application\Service\System\TokenService::class),
        \Application\Service\LoginServiceInterface::class => \DI\autowire(\Application\Service\System\LoginService::class),
        \Application\Service\ApiServiceInterface::class => \DI\autowire(\Application\Service\System\ApiService::class),
        \Application\Service\CollectionServiceInterface::class => \DI\autowire(\Application\Service\System\CollectionService::class),
        \Application\Service\CartServiceInterface::class => \DI\autowire(\Application\Service\System\CartService::class),
        \Application\Service\OrderServiceInterface::class => \DI\autowire(\Application\Service\System\OrderService::class),
    ]);
};
