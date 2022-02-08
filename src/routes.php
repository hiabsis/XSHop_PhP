<?php

use Application\Controller\System\{UserController,RoleController,MenuController,CategoryController,ApiController, ProductController,FileUploadController};
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Application\Controller\UserApi\{CartController,OrderController,CollectionController,LoginController};
return static function (\Slim\App $app) {
    // 商城可以访问的模块
    $app->group('/user', function (Group $group) {
        // 获取授权码
        $group->post('/authLogin', LoginController::class . ":authLogin");
        // 用户注册
        $group->post('/register', LoginController::class . ":register");
        // 用户注销
        $group->post('/logout', LoginController::class . ":logout");
        // 用户详情 =》 用户个人信息 权限 角色 可访问的后台菜单
        $group->post('/detail', LoginController::class . ":getUserInfo");
        // 获取首页推荐商品分类
        $group->get('/home/category', CategoryController::class . ":getIndexCategory");
        // 获取首页轮播商品
        $group->get('/home/banner', ProductController::class . ":listProductCarousel");
        // 商品详情
        $group->get('/product/detail', ProductController::class . ":detailProduct");
        // 商品收藏-添加
        $group->post('/collect/add', CollectionController::class . ":saveCollection");
        // 商品收藏-列表
        $group->get('/collect/page', CollectionController::class . ":listCollection");
        // 商品收藏-删除
        $group->get('/collect/delete', CollectionController::class . ":removeCollection");

        // 商品收藏-添加
        $group->post('/cart/add', CartController::class . ":saveCart");
        // 商品收藏-列表
        $group->get('/cart/page', CartController::class . ":listCart");
        // 商品收藏-删除
        $group->get('/cart/delete', CartController::class . ":removeCart");
        $group->post('/cart/update', CartController::class . ":updateCart");
        // 订单-添加
        $group->post('/order/add', OrderController::class . ":saveOrder");
        // 订单-查询
        $group->get('/order/page', OrderController::class . ":pageOrder");

    });
    // 后台管理系统
    $app->group('/sys', function (Group $group) {
        // 商品模块-删除
        $group->delete('/product/remove/{id}', ProductController::class . ":removeProduct");
        // 商品模块-添加
        $group->post('/product/add', ProductController::class . ":saveProduct");
        // 商品模块-列表
        $group->get('/product/page', ProductController::class . ":listProduct");
        // 商品模块-更新
        $group->put('/product/edit', ProductController::class . ":putProduct");
        // 商品模块-详情
        $group->get('/product/detail', ProductController::class . ":detailProduct");
        // 商品分类-删除
        $group->delete('/category/remove/{id}', CategoryController::class . ":removeCategory");
        // 商品分类-添加
        $group->post('/category/add', CategoryController::class . ":saveCategory");
        // 商品分类-获取
        $group->get('/category/page', CategoryController::class . ":listCategory");
        // 商品分类-更新
        $group->put('/category/edit', CategoryController::class . ":putCategory");
        // 商品分类-结构
        $group->get('/category/tree', CategoryController::class . ":treeCategory");

        // 用户模块-删除
        $group->delete('/user/remove/{id}', UserController::class . ":removeUser");
        // 用户模块-添加
        $group->post('/user/add', UserController::class . ":saveUser");
        // 用户模块-分页
        $group->get('/user/page', UserController::class . ":loadUserByPage");
        // 用户模块-更新
        $group->put('/user/edit', UserController::class . ":updateUser");
        // 用户模块-查询
        $group->get('/user/search', UserController::class . ":searchUserByPage");
        // 用户模块-修改状态
        $group->put('/user/status', UserController::class . ":editorUserStatus");

        // 角色模块-删除
        $group->delete('/role/remove', RoleController::class . ":removeRole");
        // 角色模块-保存
        $group->post('/role/save', RoleController::class . ":saveRole");
        // 角色模块-列表
        $group->get('/role/page', RoleController::class . ":loadRoleByPage");
        // 角色模块-更新
        $group->put('/role/edit', RoleController::class . ":updateRole");
        // 角色模块-查询
        $group->get('/role/search', RoleController::class . ":searchRoleByPage");


        // 接口模块-删除
        $group->delete('/permission/remove', ApiController::class . ":removeRole");
        // 接口模块-保存
        $group->post('/permission/add', ApiController::class . ":saveApi");
        // 接口模块-列表
        $group->get('/permission/page', ApiController::class . ":loadApiByPage");
        // 接口模块-更新
        $group->put('/permission/edit', ApiController::class . ":updateApi");
        // 接口模块-查询
        $group->get('/permission/search', ApiController::class . ":searchApiByPage");


        // 页面模块-删除
        $group->delete('/page/remove/{id}', MenuController::class . ":removeMenu");
        // 页面模块-添加
        $group->post('/page/add', MenuController::class . ":saveMenu");
        // 页面模块-列表
        $group->get('/page/page', MenuController::class . ":loadMenuByPage");
        // 页面模块-更新
        $group->put('/page/edit', MenuController::class . ":updateMenu");
        // 页面模块-查询
        $group->get('/page/search', MenuController::class . ":searchMenuByPage");
        // 页面模块-结构
        $group->get('/page/tree', MenuController::class . ":loadMenuByPage");

        // 文件模块-图片上传
        $group->post('/file/img/upload', FileUploadController::class . ":uploadImg");
        // 文件模块-查询
        $group->get('/file/info', FileUploadController::class . ":getResourceInfo");
        $group->get('/file/delete', FileUploadController::class . ":delete");
    });


//    $app->post('/token',\Application\Controller\UserApi\LoginController::class . ":login" );
//    $app->post('/getUserInfo',\Application\Controller\UserApi\LoginController::class . ":getUserInfo" );

//    $app->group('/shop/product/category', function (Group $group) {
//        $group->delete('/{id}', CategoryController::class . ":removeCategory");
//        $group->post('', CategoryController::class . ":saveCategory");
//        $group->get('', CategoryController::class . ":listCategory");
//        $group->put('', CategoryController::class . ":putCategory");
//        $group->get('/tree', CategoryController::class . ":treeCategory");
//        $group->get('/index', CategoryController::class . ":getIndexCategory");
//    });
//
//    $app->group('/shop/product', function (Group $group) {
//        $group->delete('/{id}', ProductController::class . ":removeProduct");
//        $group->post('', ProductController::class . ":saveProduct");
//        $group->get('', ProductController::class . ":listProduct");
//        $group->put('', ProductController::class . ":putProduct");
//        $group->get('/{id}', ProductController::class . ":detailProduct");
//        $group->get('/carousel/info', ProductController::class . ":listProductCarousel");
//    });
//    $app->group('/admin/category', function (Group $group) {
//        $group->delete('/{id}', CategoryController::class . ":removeCategory");
//        $group->post('', CategoryController::class . ":saveCategory");
//        $group->get('', CategoryController::class . ":listCategory");
//        $group->put('', CategoryController::class . ":putCategory");
//        $group->get('/tree', CategoryController::class . ":treeCategory");
//        $group->get('/index', CategoryController::class . ":getIndexCategory");
//    });
//    $app->group('/admin/product', function (Group $group) {
//        $group->delete('/{id}', ProductController::class . ":removeProduct");
//        $group->post('', ProductController::class . ":saveProduct");
//        $group->get('', ProductController::class . ":listProduct");
//        $group->put('', ProductController::class . ":putProduct");
//        $group->get('/{id}', ProductController::class . ":detailProduct");
//        $group->get('/carousel/info', ProductController::class . ":listProductCarousel");
//    });
//    $app->group('/admin/menu', function (Group $group) {
//        $group->post('/remove/{id}', MenuController::class . ":removeMenu");
//        $group->post('/save', MenuController::class . ":saveMenu");
//        $group->get('/page', MenuController::class . ":loadMenuByPage");
//        $group->put('/update', MenuController::class . ":updateMenu");
//        $group->get('/search', MenuController::class . ":searchMenuByPage");
//    });
//    $app->group('/admin/user', function (Group $group) {
//        $group->post('/remove/{id}', UserController::class . ":removeUser");
//        $group->post('/save', UserController::class . ":saveUser");
//        $group->get('/page', UserController::class . ":loadUserByPage");
//        $group->put('/update', UserController::class . ":updateUser");
//        $group->get('/search', UserController::class . ":searchUserByPage");
//        $group->get('/menu', UserController::class . ":getUserMenuTree");
//    });
//    $app->group('/admin/role', function (Group $group) {
//        $group->post('/remove/{id}', RoleController::class . ":removeRole");
//        $group->post('/save', RoleController::class . ":saveRole");
//        $group->get('/page', RoleController::class . ":loadRoleByPage");
//        $group->put('/update', RoleController::class . ":updateRole");
//        $group->get('/search', RoleController::class . ":searchRoleByPage");
//    });
//    $app->group('/admin/file', function (Group $group) {
//        $group->post('/img/upload', FileUploadController::class . ":uploadImg");
//        $group->get('/info', FileUploadController::class . ":getResourceInfo");
//    });
//
//    $app->group('/user', function (Group $group) {
//        $group->post('/authLogin', \Application\Controller\UserApi\LoginController::class . ":authLogin");
//        $group->post('/register', \Application\Controller\UserApi\LoginController::class . ":register");
//        $group->post('/logout', \Application\Controller\UserApi\LoginController::class . ":logout");
//        $group->post('/detail', \Application\Controller\UserApi\LoginController::class . ":getUserInfo");
//    });
//
//    $app->group('/system/file', function (Group $group) {
//        $group->post('/img/upload', FileUploadController::class . ":uploadImg");
//        $group->get('/info', FileUploadController::class . ":getResourceInfo");
//    });
};
