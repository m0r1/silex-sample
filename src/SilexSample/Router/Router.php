<?php
namespace SilexSample\Router;

class Router
{
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function main()
    {
        $app = $this->app;

        // TOP
        $app->match('/', $this->controller('top'))->bind('top');

        // Hello
        $app->match('/hello/{name}', $this->controller('hello'))->bind('hello');

        return $app;
    }

    private function controller($action_name)
    {
        return sprintf('\SilexSample\Controller\%s\Controller::%sAction', ucfirst(_DEVICE_), $action_name);
    }
}
