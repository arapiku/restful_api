<?php

use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Direct as Flash;
use Phalcon\Logger;
use Phalcon\Logger\Multiple as MultipleStream;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Logger\Adapter\Stream as StreamAdapter;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Security;

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * Setting up the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setDI($this);
    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.volt' => function ($view) {
            $config = $this->getConfig();

            $volt = new VoltEngine($view, $this);

            $volt->setOptions([
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_'
            ]);

            return $volt;
        },
        '.phtml' => PhpEngine::class

    ]);

    return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    $connection = new $class($params);
    
    return $connection;
});


/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    return new Flash([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});

/**
 * 404ページと500ページのハンドリング
 */
$di->set('dispatcher', function() use ($di) {
   $eventsManager = $di->getShared('eventsManager');
   $eventsManager->attach(
       'dispatch:beforeException',
       function($event, $dispatcher, $exception) {
           switch ($exception->getCode()) {
               case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
               case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                   $dispatcher->forward(
                       array(
                       'controller' => 'error',
                       'action' => 'notFound',
                        )
                    );
                   return false;
                   break;
               default:
                   $dispatcher->forward(
                       array(
                       'controller' => 'error',
                       'action' => 'uncaughtException',
                       )
                   );
                   return false;
                   break;
           }
       }
   );
   
//    $eventsManager->attach(
//        'dispatch:beforeExecuteRoute',
//        new SecurityPlugin()
//    );
   
   $dispatcher = new Dispatcher();
   $dispatcher->setEventsManager($eventsManager);
   return $dispatcher;
    
}, true);

$di->set('security', function () {
   $security = new Security();
   
   $security->setWorkFactor(12);
   
   return $security;
}, true);


/**
 * エラーログに関して（未解決）
 */
// $di->set('logger', function () use ($config) {
//     $logger =  new \Phalcon\Logger\Adapter\File($config->application->logsDir . "error.log");
//     $logger->setLogLevel(\Phalcon\Logger::INFO);
//     return $logger;
// });