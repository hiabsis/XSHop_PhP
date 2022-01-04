    <?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use \Application\Controller\UserApi\HomeController;
use \Application\Controller\System\{LoginController,FileUploadController};
use Application\Controller\Product\{CategoryController,ProductController};
return function (\Slim\App $app) {
    $app->get('/', \Application\Controller\UserApi\HomeAction::class);

    $app->group('/home', function (Group $group) {
        $group->get('/banner',HomeController::class .":getBanner");
    });


    $app->group('/shop/product/category', function (Group $group) {
        $group->delete('/{id}', CategoryController::class.":removeCategory");
        $group->post('', CategoryController::class.":saveCategory");
        $group->get('', CategoryController::class.":listCategory");
        $group->put('', CategoryController::class.":putCategory");
        $group->get('/tree', CategoryController::class.":treeCategory");
        $group->get('/index', CategoryController::class.":getIndexCategory");
    });


    $app->group('/shop/product', function (Group $group) {
        $group->delete('/{id}', ProductController::class.":removeProduct");
        $group->post('', ProductController::class.":saveProduct");
        $group->get('', ProductController::class.":listProduct");
        $group->put('', ProductController::class.":putProduct");
        $group->get('/{id}', ProductController::class.":detailProduct");
        $group->get('/carousel/info', ProductController::class.":listProductCarousel");
    });

    $app->group('/system/file', function (Group $group) {
        $group->post('/img/upload', FileUploadController::class.":uploadImg");
        $group->get('/path', FileUploadController::class.":getImgAccessPath");
    });
    $app->group('/system/user', function (Group $group) {
        $group->post('/login', LoginController::class.":login");
    });
};
