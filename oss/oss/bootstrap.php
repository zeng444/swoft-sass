<?php
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('OSS_PATH', ROOT_PATH . 'oss' . DIRECTORY_SEPARATOR);
define('CORE_PATH', ROOT_PATH . 'core' . DIRECTORY_SEPARATOR);
define('LOG_PATH', ROOT_PATH . 'logs' . DIRECTORY_SEPARATOR);

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
     * Autoload Object
     */
    include_once ROOT_PATH . 'vendor/autoload.php';

    /**
     * Autoload Object
     */
    include ROOT_PATH . 'configs/autoload.php';


    $di = new  Phalcon\Di\FactoryDefault();

    /**
     * Include Services
     */
    include ROOT_PATH . 'configs/services.php';

    include OSS_PATH . 'configs/services.php';


    /**
     * Include Autoloader
     */
    include OSS_PATH . 'configs/loader.php';

    /**
     * Starting the application
     * Assign service locator to the application
     */
    $app = new Phalcon\Mvc\Micro($di);

    /**
     * Add Rest Router
     */
    include OSS_PATH . 'configs/route.php';


    include OSS_PATH . 'configs/events.php';

    /**
     * Handle the request
     */
    $app->handle();
} catch (\Exception $e) {
    $errorMsg = $e->getMessage() . '<br>' . '<pre>' . $e->getTraceAsString() . '</pre>';
    if ($env === 'dev') {
        echo $errorMsg;
    } else {
        error_log($errorMsg, 3, ROOT_PATH . 'logs' . DIRECTORY_SEPARATOR . 'exception.log');
    }
}
