<?php
namespace SilexSample\Tests\Controller;

define('_PROJ_DIR_', __DIR__.'/../../../..');
define('_SRC_DIR_', _PROJ_DIR_.'/src');
define('_VENDOR_DIR_', _PROJ_DIR_.'/vendor');
define('_CONFIG_DIR_', _PROJ_DIR_.'/config');
define('_LOG_DIR_', _PROJ_DIR_.'/log');
// ホントは判別処理でセットする
define('_DEVICE_', 'pc');

use Silex\WebTestCase;
use SilexSample\Controller\Controller;

class ControllerTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require _SRC_DIR_.'/app.php';
        $app = require __DIR__.'/../../../../src/app.php';
        $router_class = sprintf('\SilexSample\Router\%s\Router', ucfirst(_DEVICE_));
        $router = new $router_class($app);
        $app = $router->main();
        $app['debug'] = true;
        $app['exception_handler']->disable();

        return $app;
    }

    public function testTop()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertTrue($client->getResponse()->isOk());
    }

    public function testHello()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/hello/hoge');
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('div#content:contains("Hello hoge")'));
    }
}
