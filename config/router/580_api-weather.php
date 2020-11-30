<?php
/**
 * Load the weather as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "REST Api Weather",
            "mount" => "weather-api",
            "handler" => "\Marty\Weather\ApiWeatherController",
        ],
    ]
];
