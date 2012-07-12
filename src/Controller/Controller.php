<?php
namespace Controller;

use Silex\Application;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Controller
{
  public $app;

  public function topAction(Application $app)
  {
    $template_name = 'top.html';
    $template_params = array('msg' => 'TOPページだよ');
    $app['response']->setContent($app['twig']->render($template_name, $template_params));
    return $app['response'];
  }

  public function helloAction(Application $app, $name)
  {
    if (!$name) {
      throw new NotFoundHttpException('nameがないよ');
    }

    $template_name = 'hello.html';
    $template_params = array('msg' => 'HELLOページだよ', 'name' => $name);
    $app['response']->setContent($app['twig']->render($template_name, $template_params));
    return $app['response'];
  }
}
