<?php

namespace Marty\Weather;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A controller for validating ip addresses
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class WeatherController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;


    /**
     * This is the index method action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     */
    public function indexActionGet()
    {
        $weatherModel = new WeatherModel();
        $locationModel = new LocationLookup();
        $ipModel = $this->di->ip;

        $location = $this->di->request->getGet("location");

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
        $validLocation = $weatherModel->validLocation();

        $weatherResult = $weatherModel->getFutureWeatherResult();
        $weatherHistoryResult = $weatherModel->getPreviousWeatherResult();

        $locationModel->searchByCoords($lat, $lon);
        $locationInfo = $locationModel->getResult();

        $userIpAddr = $this->di->request->getServer("REMOTE_ADDR");

        $data = [
            "location" => $location,
            "ipResult" => $ipResult ?? null,
            "isValid" => $validIp ?? null,
            "userIpAddr" => $userIpAddr,
            "weatherResult" => $weatherResult,
            "weatherHistoryResult" => $weatherHistoryResult,
            "validLocation" => $validLocation,
            "lat" => $lat ?? null,
            "lon" => $lon ?? null,
            "locationInfo" => $locationInfo,
        ];

        $title = "Väder";

        $this->di->page->add("weather/weather", $data);

        return $this->di->page->render([
            "title" => $title,
        ]);
    }
}
