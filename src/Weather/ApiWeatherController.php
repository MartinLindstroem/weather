<?php

namespace Marty\Weather;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample JSON controller to show how a controller class can be implemented.
 * The controller will be injected with $di if implementing the interface
 * ContainerInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 */
class ApiWeatherController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * This is the index method action, it handles:
     * GET METHOD mountpoint
     * GET METHOD mountpoint/
     * GET METHOD mountpoint/index
     *
     * @return array
     */
    public function indexActionGet()
    {
        // Deal with the action and return a response.

        $url = "{$this->di->request->getBaseUrl()}/weather-api";

        $data = [
            "url" => $url,
        ];

        $title = "väder API";

        $this->di->page->add("weather/weather-api", $data);

        return $this->di->page->render([
            "title" => $title,
        ]);
    }

    /**
     * method to handle post requests
     */
    public function indexActionPost() : array
    {
        $weatherModel = new WeatherModel();
        $locationModel = new LocationLookup();
        $ipModel = $this->di->ip;
        // $json = [];

        $location = $this->di->request->getPost("location");

        // Validate and get info about ip-address
        $ipModel->validateIpAddress($location);
        $ipModel->getIpAddressInfo($location);
        $validIp = $ipModel->returnIsValid();
        $ipResult = $ipModel->getResult();

        // If IP-address is valid get latitude and longitude from $ipResult
        // otherwise get them from $_GET("location")
        if ($validIp) {
            $lat = $ipResult["latitude"];
            $lon = $ipResult["longitude"];
        } else {
            $coords = explode(",", $location);
            $lat = $coords[0];
            $lon = $coords[1] ?? null;
        }

        // Get API-key and set it in WeatherModel class
        if (file_exists("config/private_keys.php")) {
            $keys = require ANAX_INSTALL_PATH . "/config/private_keys.php";
            $apiKey = $keys["openWeather"];
            $weatherModel->setApiKey($apiKey);
        } else {
            $apiKey = getenv("OPENWEATHER_KEY");
            $weatherModel->setApiKey($apiKey);
        }

        // Get previous and future weather data
        $weatherModel->getFutureWeatherInfo($lat, $lon);
        $weatherModel->getPreviousWeatherInfo($lat, $lon);

        $locationModel->searchByCoords($lat, $lon);
        $locationInfo = $locationModel->getResult();

        $weatherModel->resultAsJSON();
        $weather = $weatherModel->getWeatherJSON();
        $previousWeather = $weatherModel->getPreviousWeatherJSON();
        
        if (count($weather) > 0 && count($previousWeather) > 0) {
            $json = [
                "latitude"     => $lat ?? null,
                "longitude"    => $lon ?? null,
                "city"         => $locationInfo["address"]["city"] ?? null,
                "municipality" => $locationInfo["address"]["municipality"] ?? null,
                "county"       => $locationInfo["address"]["county"] ?? null,
                "postcode"     => $locationInfo["address"]["postcode"] ?? null,
                "country"      => $locationInfo["address"]["country"] ?? null,
                "weather"      => $weather,
                "weather_history" => $previousWeather,
            ];
        } else {
            $json = [
                "error" => "invalid location"
            ];
        }
        return [$json];
    }
}
