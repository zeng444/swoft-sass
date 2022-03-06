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
        'host' => 'mysql',
        'username' => 'root',
        'password' => 'root',
        'dbname' => 'sass_center',
        'charset' => 'utf8',
        'options' => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
        ]
    ],
    'redis' => [
        'host' => 'redis',
        'port' => '6379',
        'scheme' => 'tcp',
        'database' => 12,
    ],
    'cache' => [
        'host' => 'redis',
        'port' => '6379',
        'persistent' => false,
        'lifetime' => 172800
    ],
    'rpc' =>[
        'appId'=>'100000001',
        'appSecret'=>'e2d345c607248c40fe2d236de216dd640fd6df3e',
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
