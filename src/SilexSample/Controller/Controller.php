<?php
namespace SilexSample\Controller;

use Silex\Application;

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
            $app->abort(404, 'nameがないよ');
        }

        $template_name = 'hello.html';
        $template_params = array('msg' => 'HELLOページだよ', 'name' => $name);
        $app['response']->setContent($app['twig']->render($template_name, $template_params));
        return $app['response'];
    }
}
