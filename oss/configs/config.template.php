<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

return new \Phalcon\Config([
    'version' => '0.1',
    'logger' => [
        'file' => ROOT_PATH . 'logs/debug.log',
    ],
    'basicAuth' => [
        'key' => '1231231',
        'algs' => ['HS256'],
    ],
    'oss'=>[
        'appId'=>'12312412',
        'appSecret'=>'asdasdasdaasd',
    ],
    'application' => [
        'uploadFileDir' => ROOT_PATH . 'files/',
        'modelsDir' => CORE_PATH . 'models/',
        'imagePrefix' => '@@IMAGE_PREFIX@@',
    ],
]);
