<?php
/**
 * Registering an autoloader
 */
$loader = new \Phalcon\Loader();
$loader->registerDirs(
    [
        '../controllers',
    ]
);
$loader->registerNamespaces([
    'Application\Core\Model' => CORE_PATH . 'models/',
    'Application\Core\Components' => CORE_PATH . 'components/',
    'Application\Admin\Components' => ADMIN_PATH . 'components/',
]);
$loader->register();

