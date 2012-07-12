<?php
namespace Router;

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

    $controller_class = 'Controller\\'._DEVICE_.'\Controller';

    // TOP
    $app->match('/', $controller_class.'::topAction')->bind('top');

    // Hello
    $app->match('/hello/{name}', $controller_class.'::helloAction')->bind('hello');

    return $app;
  }
}
