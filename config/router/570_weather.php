<?php
/**
 * Load the weather as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "Weather Info",
            "mount" => "weather",
            "handler" => "\Marty\Weather\WeatherController",
        ],
    ]
];
