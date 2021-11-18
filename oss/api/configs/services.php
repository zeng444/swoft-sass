<?php

/**
 * 注入用户OAuth验证服务
 */
if (isset($config->basicAuth)) {
    $di->setShared('basicAuth', function () use ($config) {
        return new \Application\Core\Components\Internet\Http\Authorization\Basic($config->basicAuth->toArray());
    });
}


$di->setShared('storage', function () {
    return new \Application\Core\Components\Storage\Server();
});
