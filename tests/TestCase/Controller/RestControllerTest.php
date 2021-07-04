<?php

namespace CakeRestApi\Test\TestCase\Controller;

use Cake\Event\Event;
use Cake\TestSuite\IntegrationTestCase;

/**
 * CakeRestApi\Controller\RestController Test Case
 */
class CakeRestApiControllerTest extends IntegrationTestCase
{

    public $controller = null;

    /**
     * Setup method.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $request = new \Cake\Http\ServerRequest();
        $response = new \Cake\Http\Response();
        $this->controller = $this->getMockBuilder('CakeRestApi\Controller\RestController')
            ->setConstructorArgs([$request, $response])
            ->setMethods(null)
            ->getMock();
    }

    /**
     * Test beforeRender method
     *
     * @return void
     */
    public function testBeforeRender()
    {
        $this->controller->beforeRender(new Event('Controller.beforeRender'));
        $viewClass = $this->controller->viewBuilder()->getClassName();
        $this->assertEquals('CakeRestApi.Json', $viewClass);
    }

    /**
     * Clears the state used for requests.
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
        unset($this->controller);
    }
}
