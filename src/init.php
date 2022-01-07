<?php
/**
 * User: 无畏泰坦
 * Date: 2021.12.21 11:33
 * Describe 初始化参数
 */
return function (\Psr\Container\ContainerInterface $c) {
    ini_set("display_errors", 0);
    error_reporting(E_ALL ^ E_WARNING);
    $settings = $c->get(\Application\Domain\Settings\SettingsInterface::class);
    $classMapperSettings = $settings->get('classMapper');
    \Application\Helper\ClassHelper::setMapper($classMapperSettings);
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET,POST,OPTIONS,PUT,DELETE");
    header('Access-Control-Allow-Headers:x-requested-with,content-type,test-token,test-sessid');
    \Application\Helper\UploadHelper::setSaveDit($_SERVER['DOCUMENT_ROOT'].\Application\Constant\SystemConstants::$FILE_ACCESS_PATH_PREFIX);
    \Application\Constant\SystemConstants::$PRODUCT_ROOT = $_SERVER['DOCUMENT_ROOT'];
};
