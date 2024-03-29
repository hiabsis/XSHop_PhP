<?php

use Application\Controller\System\ApiController;
use Application\Controller\System\CategoryController;
use Application\Controller\System\ProductController;
use Application\Domain\Product\Category;
use Application\Domain\Product\Product;
use Application\Domain\Product\ProductInfo;
use Application\Domain\Product\ProductRelatedResource;
use Application\Domain\System\Resource;
use Application\Domain\System\User;
use  Application\Controller\UserApi\CollectionController;
use Application\Controller\UserApi\CartController;
use Application\Controller\UserApi\OrderController;
use Application\Controller\UserApi\LoginController;
use Application\Controller\System\{FileUploadController};
use Application\Controller\System\MenuController;
use Application\Controller\System\RoleController;
use Application\Controller\System\UserController;

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
                    'path' => 'E:/XShop/logs/app.log',
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

                    LoginController::class . ':login' => [
                        'username' => 'present|string|length_max:64',
                        'password' => 'present|string|length_max:64'
                    ],
                    LoginController::class . ':authLogin' => [
                        'username' => 'present|string|length_max:64',
                        'password' => 'present|string|length_max:64'
                    ],
                    LoginController::class . ':register' => [
                        'username' => 'present|string|length_max:64',
                        'password' => 'present|string|length_max:64'
                    ],

                    CategoryController::class . ':treeCategory' => [
                        'page' => 'length_max:11|min:1|to_type:integer',
                        'size' => 'length_max:4|min:1|to_type:integer'
                    ],
                    CategoryController::class . ':listCategory' => [
                        'parentId' => 'filled|string|length_max:11|to_type:integer',
                        'categoryName' => 'filled|string|length_max:32',
                    ],
                    CategoryController::class . ":removeCategory" => [
                        'id' => 'present|integer|length_max:11|to_type:integer'
                    ],
                    CategoryController::class . ":putCategory" => [
                        'categoryName' => 'present|filled|string|length_max:32',
                        'parentId' => 'present|length_max:11|to_type:integer',
                        'categoryId' => 'present|length_max:11|to_type:integer',
                        'categorySort' => 'present|length_max:4|max:1000|min:1|to_type:integer',
                        'categoryStatus' => 'present|to_type:integer|length_max:2|in:1,0',
                        'categoryType' => 'present|to_type:integer|length_max:2|in:0,1',

                    ],
                    CategoryController::class . ":saveCategory" => [
                        'categoryName' => 'present|filled|string|length_max:11|to_type:integer',
                        'parentId' => 'filled|string|length_max:11|to_type:integer',
                    ],
                    ProductController::class . ":saveProduct" => [
                        'productId' => 'integer|length_max:11',
                        'productStatus' => 'integer|length_max:2|in:1,2,3,4,5',
                        'productDesc' => 'string|length_max:255',
                        'productNumber' => 'string|length_max:11|to_type:integer',
                        'productPrice' => 'float_str|to_type:scale:2',
                        'productDetail' => 'string',
                        'productName' => 'string|length_max:32',
                        'productInfo' => 'array',
                        'resources' => 'array'
                    ],
                    ProductController::class . ":detailProduct" => [
                        'id' => 'present|filled|string|length_max:11|to_type:integer',
                    ],
                    ProductController::class . ":removeProduct" => [
                        'id' => 'present|filled|string|length_max:11|to_type:integer',
                    ],
                    ProductController::class . ":putProduct" => [
                        'id' => 'present|integer|length_max:11',
                        'status' => 'integer|length_max:2|in:0,1|to_type:integer',
                        'desc' => 'string|length_max:255',
                        'number' => 'integer|length_max:11',
                        'price' => 'float_str|to_type:scale:2',
                        'detail' => 'string',
                        'name' => 'string|length_max:32',
                        'info' => 'array',
                        'resources' => 'array'
                    ],
                    ProductController::class . ":listProduct" => [
                        'productStatus' => 'integer|length_max:2|in:1,2,3',
                        'productDesc' => 'string|length_max:255',
                        'productName' => 'string|length_max:32',
                        'categoryId' => 'string|length_max:11|to_type:integer'
                    ],
                     FileUploadController::class . ":getImgAccessPath" => [
                        'id' => 'to_type:integer|integer_str|to_type:integer',
                        'type' => 'to_type:integer|integer_str|to_type:integer',
                    ],
                     MenuController::class . ":saveMenu" => [
                        'path' => 'present|length_max:128|string',
                        'name' => 'present|length_max:64|string',
                        'component' => 'filled|length_max:64|string',
                        'parent_id' => 'present|length_max:11|integer',
                        'name_zh' => 'present|length_max:64|string',
                        'icon' => 'filled|length_max:64|string',
                    ],
                     MenuController::class . ":updateMenu" => [
                        'path' => 'present|length_max:128|string',
                        'name' => 'present|length_max:64|string',
                        'id' => 'present|length_max:11|integer',
                        'component' => 'present|length_max:64|string',
                        'parent_id' => 'present|length_max:11|integer',
                        'name_zh' => 'present|length_max:64|string',
                        'icon' => 'filled|length_max:64|string',
                    ],
                     MenuController::class.":loadMenuByPage" =>[
                        'page' => 'filled|string|to_type:integer|min:1',
                        'size' => 'filled|string|to_type:integer|max:100|min:2',
                    ],
                     MenuController::class.":searchMenuByPage" =>[
                        'data' => 'filled|length_max:64|string',
                        'page' => 'present|string|to_type:integer|min:1',
                        'size' => 'present|string|to_type:integer|max:100|min:2',
                    ],
                     MenuController::class.":removeMenu" =>[
                      'ids' => 'present|array'
                    ],
                    LoginController::class.":login"=>[
                        'username' => 'present|length_max:64',
                        'password' => 'present|length_max:64'
                    ],
                    UserController::class . ":saveUser" => [
                        'username'=>'present|string|length_max:64',
                        'password'=>'present|string|length_max:64',
                    ],
                    UserController::class . ":authLogin" => [
                        'username'=>'present|string|length_max:64',
                        'password'=>'present|string|length_max:64',
                    ],
                    UserController::class . ":editorUserStatus" => [
                        'id'=>'present|integer|length_max:64',
                        'enabled'=>'present|boolean|length_max:64|to_type:boolean',
                    ],

                   UserController::class . ":updateUser" => [
                        'username'=>'filled|string|length_max:64',
                        'password'=>'filled|string|length_max:64',
                        'id'=>'present|integer|length_max:11|min:1',
                        'roleIds'=>'filled|array',
                    ],
                   UserController::class.":loadUserByPage" =>[
                        'username'=>'filled|string|length_max:64',
                        'page' => 'filled|string|to_type:integer|min:1',
                        'size' => 'filled|string|to_type:integer|max:100|min:2',
                    ],
                   UserController::class.":searchUserByPage" =>[
                        'data' => 'filled|length_max:64|string',
                        'page' => 'present|string|to_type:integer|min:1',
                        'size' => 'present|string|to_type:integer|max:100|min:2',
                    ],
                   UserController::class.":removeUser" =>[
                        'ids' => 'present|array'
                    ],
                    RoleController::class . ":saveRole" => [
                        'name'=>'present|string|length_max:64',
                        'status'=>'present|integer|length_max:1|min:1',
                        'desc'=>'filled|string|length_max:64',
                    ],
                    RoleController::class . ":updateRole" => [
                        'name'=>'present|string|length_max:64',
                        'enabled'=>'filled|boolean|length_max:1',
                        'desc'=>'filled|string|length_max:64',
                        'id' => 'present|integer|length_max:11|min:1',
                    ],
                    RoleController::class.":loadRoleByPage" =>[
                        'id' => 'filled|integer|length_max:11|min:1',
                        'name'=>'filled|string|length_max:64',
                        'status'=>'filled|integer|length_max:1|in:1,0',
                        'page' => 'filled|string|to_type:integer|min:1',
                        'size' => 'filled|string|to_type:integer|max:100|min:2',
                    ],

                    ApiController::class.":removeApi" =>[
                        'ids' => 'present|array'
                    ],
                    ApiController::class . ":saveApi" => [
                        'name'=>'present|string|length_max:64',
                        'status'=>'filled|integer|length_max:1',
                        'type'=>'filled|integer|length_max:2',
                        'permission'=>'filled|string|length_max:64',
                        'path'=>'present|string|length_max:64',
                    ],
                    ApiController::class . ":updateApi" => [
                        'name'=>'present|string|length_max:64',
                        'path'=>'present|string|length_max:64',
                        'id' => 'present|integer|length_max:11|min:1',
                        'status'=>'boolean|length_max:1',
                        'type'=>'filled|integer|length_max:2',
                        'permission'=>'filled|string|length_max:64',
                    ],
                   ApiController::class.":loadApiByPage" =>[
                       'name'=>'filled|string|length_max:64',
                       'status'=>'filled|integer|length_max:1',
                       'type'=>'filled|integer|length_max:2',
                       'permission'=>'filled|string|length_max:64',
                       'path'=>'filled|string|length_max:64',
                        'page' => 'filled|string|to_type:integer|min:1',
                        'size' => 'filled|string|to_type:integer|max:100|min:2',
                    ],
                    RoleController::class.":removeRole" =>[
                        'ids' => 'present|array'
                    ],
                    CollectionController::class .':saveCollection' =>[
                        'user_id' => 'present|length_max:11|to_type:integer',
                        'product_id' =>  'present|integer|length_max:11|to_type:integer'
                    ],
                    CollectionController::class .':listCollection' =>[
                        'user_id' => 'present|length_max:11|to_type:integer',
                        'page' => 'filled|string|to_type:integer|min:1',
                        'size' => 'filled|string|to_type:integer|max:100|min:2',
                    ],
                    CollectionController::class .':removeCollection' =>[
                        'user_id' => 'present|length_max:11|to_type:integer',
                        'collect_id' =>  'present|length_max:11'
                    ],
                    CartController::class .':saveCart' =>[
                        'user_id' => 'present|length_max:11|to_type:integer',
                        'product_id' =>  'present|integer|length_max:11|to_type:integer'
                    ],
                    CartController::class .':listCart' =>[
                        'user_id' => 'present|length_max:11|to_type:integer',
                        'page' => 'filled|string|to_type:integer|min:1',
                        'size' => 'filled|string|to_type:integer|max:100|min:2',
                    ],
                    CartController::class .':removeCart' =>[
                        'user_id' => 'present|length_max:11|to_type:integer',
                        'cart_id' =>  'present|length_max:11'
                    ],
                    CartController::class .':updateCart' =>[
                        'user_id' => 'present|length_max:11|to_type:integer',
                        'cart_id' =>  'present|length_max:11'
                    ],
                    OrderController::class.':saveOrder' => [
                        'user_id' => 'present|length_max:11|to_type:integer',
                        'products' =>  'present|array',
                        'address_id' => 'present|length_max:11|to_type:integer',
                    ],
                    OrderController::class.':pageOrder' => [
                        'user_id' => 'present|length_max:11|to_type:integer',
                    ],
                    ProductController::class.":hotProduct" => [
                        'page' => 'present|length_max:11|to_type:integer',
                        'size' => 'present|length_max:11|to_type:integer',
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
                        'id' => 'id',
                        'userId' => 'user_id',
                        'categoryId' => 'category_id',
                        'status' => 'status',
                        'desc' => 'desc',
                        'detail' => 'detail',
                        'number' => 'number',
                        'name' => 'name',
                        'price' => 'price'
                    ],
                    Resource::class => [
                        'id' => 'resourceId',
                        'userId' => 'userId',
                        'size' => 'fileSize',
                        'name' => 'fileName',
                        'mine' => 'mine',
                        'url' => 'url',
                        'createTime' => 'createTime',
                    ],
                    ProductInfo::class => [
                        'id' => 'productInfoId',
                        'productId' => 'productId',
                        'name' => 'name',
                        'value' => 'value',
                        'type' => 'type',
                        'createTime' => 'createTime',
                    ],
                    ProductRelatedResource::class => [
                        'id' => 'id',
                        'productId' => 'productId',
                        'resourceId' => 'resourceId',
                        'resourceType' => 'resourceType'
                    ],
                    User::class => [
                        'id' => "id",
                        'username' => 'username',
                        'password' => 'password'
                    ]
                ]
            ]);
        },
    ]);
};

