<?php

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app = new Application();

$app->register(new SessionServiceProvider());
$app['session.storage.handler'] = null;

$app->register(new UrlGeneratorServiceProvider());

$app->register(new TwigServiceProvider(), array(
    'twig.path'    => array(_PROJ_DIR_.'/views/'._DEVICE_),
));
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    // add custom globals, filters, tags, ...
    return $twig;
}));

$app['response'] = new Response();

$_config = parse_ini_file(_CONFIG_DIR_.'/application.ini', true);
$app['config'] = $_config[apache_getenv('APPLICATION_ENV')];

$_database_config = parse_ini_file(_CONFIG_DIR_.'/database.ini', true);
$app->register(new DoctrineServiceProvider(), array(
    'dbs.options' => $_database_config[apache_getenv('APPLICATION_ENV')],
));

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => _LOG_DIR_.'/'._DEVICE_.'/app.log',
    'monolog.name'    => 'silex-samle['._DEVICE_.']',
));

return $app;
