<?php

use Application\Controller\Product\CategoryController;
use Application\Controller\Product\ProductController;
use Application\Controller\System\LoginController;
use Application\Domain\Product\Category;
use Application\Domain\Product\Product;
use Application\Domain\Product\ProductInfo;
use Application\Domain\Product\ProductRelatedResource;
use Application\Domain\System\Resource;
use Application\Domain\System\User;

return function (\DI\ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        \Application\Domain\Settings\SettingsInterface::class => function () {
            return new \Application\Domain\Settings\Settings([
                'displayErrorDetails' => true, // Should be set to false in production
                'logError' => false,
                'logErrorDetails' => true,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => 'E:/work_pace\shop/XSHOP_PD/XShop/logs/app.log',
                    'level' => \Monolog\Logger::DEBUG,
                ],
                'db' => [
                    'dsn' => 'mysql:host=127.0.0.1;dbname=xshop;charset=utf8;port=3306',
                    'user' => 'root',
                    'pass' => '123456'
                ],
                'redis' => [
                    'host' => '127.0.0.1',
                    'port' => 6379
                ],
                //校验规则
                'validator' => [

                    LoginController::class.':login' => [
                        'username' => 'present|string|length_max:64',
                        'password' => 'present|string|length_max:64'
                    ],
                    CategoryController::class.':treeCategory' => [
                        'page' => 'length_max:11|min:1|to_type:integer',
                        'size' => 'length_max:4|min:1|to_type:integer'
                    ],
                    CategoryController::class.':listCategory' => [
                        'parentId' => 'filled|string|length_max:11|to_type:integer',
                        'categoryName' => 'filled|string|length_max:32',
                    ],
                    CategoryController::class.":removeCategory" => [
                        'id' => 'filled|string|length_max:11|to_type:integer'
                    ],
                    CategoryController::class.":putCategory" => [
                        'categoryName' => 'present|filled|string|length_max:32',
                        'parentId' => 'present|string|length_max:11|to_type:integer',
                        'categoryId' => 'present|string|length_max:11|to_type:integer',
                        'categorySort' => 'present|string|length_max:4|max:1000|min:1|to_type:integer',
                        'categoryStatus' => 'present|string|to_type:integer|length_max:2|in:1,0',
                        'categoryType' => 'present|string|to_type:integer|length_max:2|in:0,1',

                    ],
                    CategoryController::class.":saveCategory" => [
                        'categoryName' => 'present|filled|string|length_max:11|to_type:integer',
                        'parentId' => 'filled|string|length_max:11|to_type:integer',
                    ],
                    ProductController::class.":saveProduct" =>[
                        'productId' => 'integer|length_max:11',
                        'productStatus' => 'integer|length_max:2|in:1,2,3,4,5',
                        'productDesc' => 'string|length_max:255',
                        'productNumber' => 'integer|length_max:11',
                        'productPrice' => 'float_str|to_type:scale:2',
                        'productDetail' => 'string',
                        'productName' => 'string|length_max:32',
                        'productInfo' =>'array',
                        'resources' => 'array'
                    ],
                    ProductController::class.":detailProduct" =>[
                        'id' => 'present|filled|string|length_max:11|to_type:integer',
                    ],
                    ProductController::class.":removeProduct" =>[
                        'id' => 'present|filled|string|length_max:11|to_type:integer',
                    ],
                    ProductController::class.":putProduct" =>[
                        'productId' => 'present|integer|length_max:11',
                        'productStatus' => 'integer|length_max:2|in:1,2,3',
                        'productDesc' => 'string|length_max:255',
                        'productNumber' => 'integer|length_max:11',
                        'productPrice' => 'float_str|to_type:scale:2',
                        'productDetail' => 'string',
                        'productName' => 'string|length_max:32',
                        'productInfo' =>'array',
                        'resources' => 'array'
                    ],
                    ProductController::class.":listProduct" =>[
                        'productStatus' => 'integer|length_max:2|in:1,2,3',
                        'productDesc' => 'string|length_max:255',
                        'productName' => 'string|length_max:32',
                        'categoryId' => 'string|length_max:11|to_type:integer'
                    ],
                    \Application\Controller\System\FileUploadController::class.":getImgAccessPath" =>[
                        'id' => 'to_type:integer|integer_str|to_type:integer',
                        'type' => 'to_type:integer|integer_str|to_type:integer',
                    ]


                ],
                // 类属性与前端传值之间的映射
                'classMapper' => [
                    Category::class => [
                        'id' => 'categoryId',
                        'name' => 'categoryName',
                        'parentId' => 'parentId',
                        'status' => 'categoryStatus',
                        'type' => 'categoryType',
                        'sort' => 'categorySort'
                    ],
                    Product::class => [
                        'id' => 'productId',
                        'userId' => 'userId',
                        'categoryId' => 'categoryId',
                        'status' => 'productStatus',
                        'desc' => 'productDesc',
                        'detail' => 'productDetail',
                        'number' => 'productNumber',
                        'name' => 'productName',
                        'price' => 'productPrice'
                    ],
                    Resource::class =>[
                        'id' => 'resourceId',
                        'userId' =>'userId',
                        'size' =>'fileSize',
                        'name' =>'fileName',
                        'mine' =>'mine',
                        'url' =>'url',
                        'createTime' =>'createTime',
                    ],
                    ProductInfo::class =>[
                        'id' => 'productInfoId',
                        'productId' =>'productId',
                        'name' =>'name',
                        'value' =>'value',
                        'type' =>'type',
                        'createTime' =>'createTime',
                    ],
                    ProductRelatedResource::class =>[
                        'id' => 'id',
                        'productId' => 'productId',
                        'resourceId' => 'resourceId',
                        'resourceType' => 'resourceType'
                    ],
                    User::class =>[
                        'id' => "id",
                        'username' => 'username',
                        'password' => 'password'
                    ]
                ]
            ]);
        },
    ]);
};

