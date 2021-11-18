<?php


use Phalcon\Logger\Adapter\File as FileAdapter;


/**
 * Sets the config
 */
$di->setShared('config', function () use ($config) {
    return $config;
});

/**
 * 环境变量设置
 */
$di->setShared("env", function () {
    return (isset($_SERVER['SITE_ENV']) && $_SERVER['SITE_ENV']) ? strtolower($_SERVER['SITE_ENV']) : 'prod';
});

/**
 *
 */
$di->setShared('apiResponse', function () {
    return new Application\Core\Components\Internet\Http\Response();
});


/**
 * 日志服务
 */
if (isset($config->logger)) {
    $di->setShared('logger', function () use ($config) {
        return new FileAdapter($config->logger->file);
    });
}


/**
 * 增加安全的request对象
 */
$di->set('request', [
    'className' => 'Application\Core\Components\Internet\Http\Request',
]);


