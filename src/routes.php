<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use \Application\Controller\UserApi\HomeController;
use \Application\Controller\System\{LoginController, FileUploadController};
use Application\Controller\Product\{CategoryController, ProductController};
use  \Application\Controller\AdminApi\MenuController;

return function (\Slim\App $app) {


    $app->group('/home', function (Group $group) {
        $group->get('/banner', HomeController::class . ":getBanner");
    });


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
    $app->group('/user', function (Group $group) {
        $group->post('/login', \Application\Controller\UserApi\LoginController::class . ":login");
    });

    $app->group('/system/file', function (Group $group) {
        $group->post('/img/upload', FileUploadController::class . ":uploadImg");
        $group->get('/info', FileUploadController::class . ":getResourceInfo");
    });
};
