<?php




if (preg_match('/\.(?:png|jpg|jpeg|gif)$/', $_SERVER["REQUEST_URI"])){
    return false;    // 直接返回请  求的文件
}



require __DIR__ . '/../vendor/autoload.php';


$containerBuilder = new \DI\ContainerBuilder();
$setting = require __DIR__ . '/../src/setting.php';
$setting($containerBuilder);
$dependencies = require __DIR__ . '/../src/dependencies.php';
$dependencies($containerBuilder);
$models = require __DIR__ . '/../src/model.php';
$models($containerBuilder);
$services = require __DIR__ . '/../src/service.php';
$services($containerBuilder);


$container = $containerBuilder->build();

$init = require __DIR__ . '/../src/init.php';
$init($container);



\Slim\Factory\AppFactory::setContainer($container);
$app = \Slim\Factory\AppFactory::create();

// Register middleware
$middleware = require __DIR__ . '/../src/middleware.php';
$middleware($app);



// Register routes
$callableResolver = $app->getCallableResolver();
$routes = require __DIR__ . '/../src/routes.php';
$routes($app);

/** @var SettingsInterface $settings */
$settings = $container->get(\Application\Domain\Settings\SettingsInterface::class);
$displayErrorDetails = $settings->get('displayErrorDetails');
$logError = $settings->get('logError');
$logErrorDetails = $settings->get('logErrorDetails');




$serverRequestCreator = \Slim\Factory\ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();


// Create Error Handler
$responseFactory = $app->getResponseFactory();
//$errorHandler = new \Application\Handler\ErrorHandler($callableResolver, $responseFactory);
//$errorHandler->setLogger($container->get(\Psr\Log\LoggerInterface::class));

// Create Shutdown Handler
//$shutdownHandler = new \Application\Handler\ShutdownHandler($request, $errorHandler, $displayErrorDetails);
//register_shutdown_function($shutdownHandler);


//$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, $logError, $logErrorDetails);
//$errorMiddleware->setDefaultErrorHandler($errorHandler);

// Run App & Emit Response
$response = $app->handle($request);
$responseEmitter = new \Slim\ResponseEmitter();
$responseEmitter->emit($response);

//$app->run();
