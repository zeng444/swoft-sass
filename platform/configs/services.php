<?php


use Phalcon\Cache\Frontend\Data as FrontData;
use Phalcon\Cache\Backend\Redis as BackendCache;
use Application\Core\Components\Rpc\Client as RPCClient;

/**
 * Services are globally registered in this file
 *
 * @var \Phalcon\Config $config
 */
$di->setShared('config', function () use ($config) {
    return $config;
});

/**
 * Sets the env
 */
$di->setShared("env", function () {
    return (isset($_SERVER['SITE_ENV']) && $_SERVER['SITE_ENV']) ? strtolower($_SERVER['SITE_ENV']) : 'prod';
});

/**
 * redis服务
 */
if (isset($config->redis)) {
    $di->setShared('redis', function () use ($config) {
        return new Predis\Client($config->redis->toArray());
    });
}


/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () use ($config, $di) {
    $dbConfig = $config->database->toArray();
    $adapter = $dbConfig['adapter'];
    unset($dbConfig['adapter']);
    $class = 'Phalcon\Db\Adapter\Pdo\\' . $adapter;
    $db = new $class($dbConfig);
    return $db;
});


if (isset($config->rpc)) {
    $di->setShared('rpc', function () use ($config, $di) {
        return new RPCClient($config->rpc->toArray());
    });
}


/**
 * cache服务
 */
if (isset($config->cache)) {
    $di->set('cache', function () use ($config) {
        $frontCache = new FrontData(["lifetime" => $config->cache->lifetime]);
        return new BackendCache($frontCache, $config->cache->toArray());
    });
}




