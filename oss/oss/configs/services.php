<?php

use Application\Core\Components\Internet\Http\Authorization\Oss as OssAuthorization;

if (isset($config->oss)) {
    $di->setShared('ossAuth', function () use ($config) {
        return new OssAuthorization($config->oss->toArray());
    });
}

$di->setShared('storage', function () {
    return new \Application\Core\Components\Storage\Server();
});