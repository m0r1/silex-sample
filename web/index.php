<?php
ini_set('phar.readonly', 'Off');
ini_set('phar.require_hash', 'Off');
ini_set('detect_unicode', 'Off');

define('_PROJ_DIR_', __DIR__.'/..');
// ホントは判別処理でセットする
define('_DEVICE_', 'pc');
define('_BASE_ROUTERS_DIR_', _PROJ_DIR_.'/routers');
define('_ROUTERS_DIR_', _BASE_ROUTERS_DIR_.'/'._DEVICE_);
define('_BASE_CONTROLLERS_DIR_', _PROJ_DIR_.'/controllers');
define('_CONTROLLERS_DIR_', _BASE_CONTROLLERS_DIR_.'/'._DEVICE_);
define('_CONFIG_DIR_', _PROJ_DIR_.'/config');
define('_LOG_DIR_', _PROJ_DIR_.'/log');

require_once _PROJ_DIR_.'/vendor/autoload.php';
$app = require _PROJ_DIR_.'/src/app.php';

$app->after(function() use($app) {
    if (_DEVICE_ == 'fp') {
      // 携帯の時は半角カナに
      $content = mb_convert_kana($app['response']->getContent(), 'k', 'UTF-8');
      $app['response']->setContent($content);
    }
});

$app->error(function (\Exception $e, $code) use ($app){
    $app['monolog']->addError("(code:$code)".$e->getFile()."(".$e->getLine().")\n".$e->getMessage());

    if ($code == 404 || $e instanceof Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
      $template_name = '404error.html';
    } else {
      $template_name = '500error.html';
    }

    return new Symfony\Component\HttpFoundation\Response($app['twig']->render($template_name), $code);
});

require_once _ROUTERS_DIR_.'/Router.php';
$router_class = 'Router\\'.ucfirst(_DEVICE_).'\Router';
$router = new $router_class($app);

try {
  $app = $router->main();
  $app->run();
} catch (\Exception $e) {
  throw $e;
}
