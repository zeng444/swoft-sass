<?php
/**
 * Services are globally registered in this file
 *
 * @var \Phalcon\Config $config
 */

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\View;
use Application\Admin\Components\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Events\Event;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Http\Response\Cookies;

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri(isset($config->application->baseUri) ? $config->application->baseUri : '/');

    return $url;
});

/**
 * Setting up the view component
 */
$di->setShared('view', function () use ($config) {

    $view = new View();

    $view->setViewsDir('../views/');

    $view->registerEngines([
        '.volt' => function ($view, $di) use ($config) {

            $volt = new VoltEngine($view, $di);

            $volt->setOptions([
                'compiledPath' => '../caches/',
                'compiledSeparator' => '_'
            ]);
            return $volt;
        },
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ]);

    return $view;
});

$di->setShared('controllerApiResponse', function () {
    return new Application\Admin\Components\Api\Response();
});


/**
 *
 */
$di->set('dispatcher', function () {
    $eventsManager = new EventsManager();
    $eventsManager->attach('dispatch:beforeException', function (Event $event, MvcDispatcher $dispatcher, Exception $exception) {
        if ($exception && in_array($exception->getCode(), [2, 5])) {
            $dispatcher->forward(['controller' => 'index', 'action' => 'show404',]);
            return false;
        }
    });
//    $eventsManager->attach("dispatch:afterDispatchLoop", function (Event $event, MvcDispatcher $dispatcher) {
//        $className = $dispatcher->getHandlerClass(); //return this->_handlerName即可
//        return $className;
//    });
    $dispatcher = new MvcDispatcher();
    $dispatcher->setEventsManager($eventsManager);
    return $dispatcher;

}, true);


/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});

$di->set('user', function () {
    return new Application\Admin\Components\Web\Auth\User();
});

$di->set(
    'cookies',
    function () {
        $cookies = new Cookies();
        $cookies->useEncryption(false);
        return $cookies;
    }
);