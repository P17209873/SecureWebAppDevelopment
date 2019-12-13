<?php

use PHPUnit\Framework\TestCase;

/*require 'vendor/autoload.php';

require __DIR__ . '/app/dependencies.php';

require __DIR__ . '/app/routes.php';*/

class bootstrapTest extends TestCase
{
    /*public function setUp()
    {
        $settings = require __DIR__ . '/app/settings.php';

        $container = new \Slim\Container($settings);



        $app = new \Slim\App($container);

        require __DIR__ . '/app/routes.php';

        $app->run();
    }

    public function testGetRequestReturnsEcho()
    {
        // instantiate action
        $settings = require __DIR__ . '/app/settings.php';
        $container = new \Slim\Container($settings);
        $app = new \Slim\App($container);
        //$action = new \App\Action\EchoAction();

        // We need a request and response object to invoke the action
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD' => 'POST',
                'REQUEST_URI' => '/processmessage',
                'QUERY_STRING'=>'foo=bar']
        );
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = new \Slim\Http\Response();

        // run the controller action and test it
        //$response = $action($request, $response, []);
        $response = $app($request, $response, []);
        $this->assertSame((string)$response->getBody(), '{"foo":"bar"}');
    }*/

    public function testFakeTest() {

        $this->assertEquals(false, true);
    }

}


