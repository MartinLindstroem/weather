<?php

namespace Marty\Weather;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the SampleController.
 */
class ApiWeatherControllerFailTest extends TestCase
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


    public function testIndexActionPost()
    {
        // Setup the controller
        $controller = new ApiWeatherController();
        $controller->setDI($this->di);

        // Setup request object
        $request = $this->di->get("request");
        // $page = $this->di->get("page");

        $request->setPost("location", "hej");

        // Call method and get response body
        $res = $controller->indexActionPost();
        // $body = $res->getBody();

        $result = $res[0]["error"];
        $exp = "invalid location";

        // Test when $_POST["location"] is latitude and longitude
        $this->assertContains($exp, $result);


        $request->setPost("location", "123.5");

        $res = $controller->IndexActionPost();

        $result = $res[0]["error"];
        $exp = "invalid location";

        $this->assertContains($exp, $result);
    }
}
