<?php
declare(strict_types=1);


use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use \Application\Helper\RedisHelper;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(\Application\Domain\Settings\SettingsInterface::class);
            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);
            $processor = new UidProcessor();
            $logger->pushProcessor($processor);
            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        PDO::class => function(ContainerInterface $c){
            $settings = $c->get(\Application\Domain\Settings\SettingsInterface::class);
            $databaseSetting  = $settings->get('db');
            $dsn = $databaseSetting['dsn'];
            $user =  $databaseSetting['user'];
            $pass = $databaseSetting['pass'];
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //错误模式
                PDO::ATTR_CASE => PDO::CASE_NATURAL, // 自然名称
                PDO::ATTR_PERSISTENT => true,
                PDO::ERRMODE_EXCEPTION => true ,// 抛出异常,
                 PDO::ATTR_EMULATE_PREPARES => false
            ];
            $pdo = new PDO($dsn, $user, $pass, $options);
            return $pdo;
        },
        \Application\Domain\Settings\ValidatorRuleInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(\Application\Domain\Settings\SettingsInterface::class);
            $validatorSettings = $settings->get('validator');
            return new \Application\Domain\Settings\ValidatorRule($validatorSettings);
        },
        Redis::class =>  function (ContainerInterface $c) {
            $redis = new \Redis();
            $settings = $c->get(\Application\Domain\Settings\SettingsInterface::class);
            $redisSetting  = $settings->get('redis');

            $redis->connect($redisSetting['host'], $redisSetting['port']);
            $redis->ping();
            return $redis;
        },
        \Application\Helper\RedisHelper::class => function (ContainerInterface $c) {
            $redis = $c->get(Redis::class);
            return new RedisHelper($redis);
        },
        \Medoo\Medoo::class => function (ContainerInterface $c) {
            return new Medoo\Medoo([
                'type' => 'mysql',
                'host' => 'localhost',
                'database' => 'xshop',
                'username' => 'root',
                'password' => '123456',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_general_ci',
                'port' => 3306,
                'option' => [
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            ]);
        }
    ]);
};
