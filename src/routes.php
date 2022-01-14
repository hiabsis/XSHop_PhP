<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use \Application\Controller\UserApi\HomeController;
use \Application\Controller\System\{LoginController, FileUploadController};
use Application\Controller\Product\{CategoryController, ProductController};
use  \Application\Controller\AdminApi\MenuController;
use \Application\Controller\AdminApi\UserController;
use \Application\Controller\AdminApi\RoleController;
return function (\Slim\App $app) {


    $app->group('/home', function (Group $group) {
        $group->get('/banner', HomeController::class . ":getBanner");
    });
    $app->post('/token',\Application\Controller\UserApi\LoginController::class . ":login" );

    $app->group('/shop/product/category', function (Group $group) {
        $group->delete('/{id}', CategoryController::class . ":removeCategory");
        $group->post('', CategoryController::class . ":saveCategory");
        $group->get('', CategoryController::class . ":listCategory");
        $group->put('', CategoryController::class . ":putCategory");
        $group->get('/tree', CategoryController::class . ":treeCategory");
        $group->get('/index', CategoryController::class . ":getIndexCategory");
    });


    $app->group('/shop/product', function (Group $group) {
        $group->delete('/{id}', ProductController::class . ":removeProduct");
        $group->post('', ProductController::class . ":saveProduct");
        $group->get('', ProductController::class . ":listProduct");
        $group->put('', ProductController::class . ":putProduct");
        $group->get('/{id}', ProductController::class . ":detailProduct");
        $group->get('/carousel/info', ProductController::class . ":listProductCarousel");
    });

    $app->group('/admin/menu', function (Group $group) {
        $group->post('/remove/{id}', MenuController::class . ":removeMenu");
        $group->post('/save', MenuController::class . ":saveMenu");
        $group->get('/page', MenuController::class . ":loadMenuByPage");
        $group->put('/update', MenuController::class . ":updateMenu");
        $group->get('/search', MenuController::class . ":searchMenuByPage");
    });
    $app->group('/admin/user', function (Group $group) {
        $group->post('/remove/{id}',  UserController::class. ":removeUser");
        $group->post('/save', UserController::class . ":saveUser");
        $group->get('/page', UserController::class . ":loadUserByPage");
        $group->put('/update', UserController::class . ":updateUser");
        $group->get('/search', UserController::class . ":searchUserByPage");
        $group->get('/menu', UserController::class . ":getUserMenuTree");
    });
    $app->group('/admin/role', function (Group $group) {
        $group->post('/remove/{id}',  RoleController::class. ":removeRole");
        $group->post('/save', RoleController::class . ":saveRole");
        $group->get('/page', RoleController::class . ":loadRoleByPage");
        $group->put('/update', RoleController::class . ":updateRole");
        $group->get('/search', RoleController::class . ":searchRoleByPage");
    });


    $app->group('/user', function (Group $group) {
        $group->post('/login', \Application\Controller\UserApi\LoginController::class . ":login");
        $group->post('/register', \Application\Controller\UserApi\LoginController::class . ":register");
        $group->post('/logout', \Application\Controller\UserApi\LoginController::class . ":logout");
    });

    $app->group('/system/file', function (Group $group) {
        $group->post('/img/upload', FileUploadController::class . ":uploadImg");
        $group->get('/info', FileUploadController::class . ":getResourceInfo");
    });
};
