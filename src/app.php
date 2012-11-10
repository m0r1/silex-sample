<?php

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Symfony\Component\HttpFoundation\Response;

$app = new Application();

$app->register(new SessionServiceProvider());
$app['session.storage.handler'] = null;

$app->register(new UrlGeneratorServiceProvider());

$app->register(new TwigServiceProvider(), array(
    'twig.path'    => array(_PROJ_DIR_.'/views/'._DEVICE_),
));
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    // add custom globals, filters, tags, ...
    $twig->addGlobal('device', _DEVICE_);
    return $twig;
}));

$app['response'] = new Response();

$_app_env = apache_getenv('APPLICATION_ENV');

$_config = parse_ini_file(_CONFIG_DIR_.'/application.ini', true);
$app['config'] = $_config[$_app_env];

$_database_config = parse_ini_file(_CONFIG_DIR_.'/database.ini', true);
$app->register(new DoctrineServiceProvider(), array(
    'dbs.options' => $_database_config[$_app_env],
));

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => _LOG_DIR_.'/'._DEVICE_.'/app.log',
    'monolog.name'    => 'silex-samle['._DEVICE_.']',
));

return $app;
