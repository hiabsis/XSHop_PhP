<?php
declare(strict_types=1);

use App\Application\Middleware\SessionMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(\Application\Middleware\JsonBodyParserMiddleware::class);
    $app->add(\Application\Middleware\FirewallMiddleware::class);
};
