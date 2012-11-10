<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

define('_PROJ_DIR_', __DIR__.'/..');
define('_SRC_DIR_', _PROJ_DIR_.'/src');
define('_VENDOR_DIR_', _PROJ_DIR_.'/vendor');
// ホントは判別処理でセットする
define('_DEVICE_', 'pc');
define('_BASE_ROUTERS_DIR_', _SRC_DIR_.'/Router');
define('_CONFIG_DIR_', _PROJ_DIR_.'/config');
define('_LOG_DIR_', _PROJ_DIR_.'/log');

require_once _VENDOR_DIR_.'/autoload.php';
$app = require _SRC_DIR_.'/app.php';

$app->after(function () use ($app) {
    if (_DEVICE_ == 'fp') {
        // 携帯の時は半角カナに
        $content = mb_convert_kana($app['response']->getContent(), 'k', 'UTF-8');
        $app['response']->setContent($content);
    }
});

$app->error(function (\Exception $e, $code) use ($app){
    $app['monolog']->addError(sprintf("(code: %s) %s (%s)\n %s", $code, $e->getFile(), $e->getLine(), $e->getMessage()));

    if ($code == 404 || $e instanceof NotFoundHttpException) {
        $template_name = '404error.html';
    } else {
        $template_name = '500error.html';
    }

    return new Response($app['twig']->render($template_name), $code);
});

$router_class = sprintf('\SilexSample\Router\%s\Router', ucfirst(_DEVICE_));
$router = new $router_class($app);
$app = $router->main();
$app->run();
