<?php

namespace Marty\Weather;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the SampleController.
 */
class WeatherControllerFailTest extends TestCase
{

    protected $di;


    /**
     * Prepare before each test.
     */
    protected function setUp()
    {
        global $di;

        // Setup di
        $this->di = new DIFactoryConfig();
        $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        // Use a different cache dir for unit test
        $this->di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        // View helpers uses the global $di so it needs its value
        $di = $this->di;
    }

    /**
     * Test the route "index".
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function testIndexActionGetFail()
    {
        // Setup the controller
        $controller = new WeatherController();
        $controller->setDI($this->di);

        // Setup request object
        $request = $this->di->get("request");
        $page = $this->di->get("page");

        // Set $_GET
        $request->setGet("location", "123");

        // Call method and get response body
        $res = $controller->indexActionGet();
        $body = $res->getBody();

        $exp = "Kunde inte hitta positionen, var snäll och ange en giltig position.";

        // Test when $_GET["location] is latitude and longitude
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertContains($exp, $body);
    }
}
