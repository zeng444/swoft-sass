<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

return new \Phalcon\Config([
    'version' => '0.1',
    'logger' => [
        'file' => ROOT_PATH . 'logs/debug.' . date('Ymd') . '.log'
    ],
    'database' => [
        'adapter' => 'Mysql',
        'host' => '@@DB_SERVER@@',
        'username' => '@@DB_SERVER_USERNAME@@',
        'password' => '@@DB_SERVER_PASSWORD@@',
        'dbname' => '@@DB_NAME@@',
        'charset' => 'utf8',
        'options' => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
        ]
    ],
    'redis' => [
        'host' => '@@REDIS_SERVER@@',
        'port' => '@@REDIS_PORT@@',
        'scheme' => 'tcp',
        'database' => 12,
    ],
    'cache' => [
        'host' => '@@REDIS_SERVER@@',
        'port' => '@@REDIS_PORT@@',
        'persistent' => false,
        'lifetime' => 172800
    ],
    'rpc' =>[
        'appId'=>'460601755',
        'appSecret'=>'915a04c607248c40fe2f116de216dd640fd67fce',
    ],
    'platform_service' => [
        'baseUrl' => 'http://platform_service.janfish.cn/',
    ],
    'application' => [
        'name' => 'YOU_APPLICATION_NAME',
        'uploadFileDir' => ADMIN_PATH . 'public/files/',
        'modelsDir' => CORE_PATH . 'models/',
        'baseUrl' => 'http://YOUR_LOCAL_DOMAIN/',
        'imagePrefix' => 'http://demo.cn/files/'
    ]
]);
