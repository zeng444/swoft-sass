<?php

define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP_PATH', ROOT_PATH . 'app' . DIRECTORY_SEPARATOR);
define('ADMIN_PATH', ROOT_PATH . 'admin' . DIRECTORY_SEPARATOR);
define('CORE_PATH', ROOT_PATH . 'core' . DIRECTORY_SEPARATOR);
define('LOG_PATH', ROOT_PATH . 'logs' . DIRECTORY_SEPARATOR);

use Application\Core\Models\Configuration;

try {

    /**
     * Switch the configuration
     */
    $env = isset($_ENV['SITE_ENV']) ? strtolower($_ENV['SITE_ENV']) : 'prod';

    /**
     * Read the configuration
     */
    if ($env === 'dev') {
        $config = include ROOT_PATH . "configs/dev.php";
    } else {
        $config = include ROOT_PATH . "configs/config.php";
    }

    /**
     * Include Autoloader
     */
    include ADMIN_PATH . 'configs/loader.php';


    $di = new Phalcon\Di\FactoryDefault();

    /**
     * Include Services
     */
    include ROOT_PATH . 'configs/services.php';

    include ADMIN_PATH . 'configs/services.php';

    /**
     * Starting the application
     * Assign service locator to the application
     */
    $application = new \Phalcon\Mvc\Application($di);

    Configuration::loadConfigurations();

    /**
     * Handle the request
     */
    echo $application->handle()->getContent();


} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
