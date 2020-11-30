<?php

namespace Marty\Weather;

/**
 * Model for validating IP-addresses
 *
 */
class WeatherModel
{
    /**
     * @var string $apiKey Api-key to use for curl
     * @var array  $futureWeather for saving the returned data from weather forecast
     * @var array  $previousWeather for saving the returned data from historical weather
     */

    private $apiKey;
    private $futureWeather;
    private $previousWeather;
    private $futureWeatherJSON = [];
    private $previousWeatherJSON = [];
    private $isValidLocation = false;
    // private $json;

    /**
     * Get weather forecast information of location
     */
    public function getFutureWeatherInfo($lat, $lon)
    {
        $curl = curl_init("https://api.openweathermap.org/data/2.5/onecall?lat=".$lat."&lon=".$lon."&exclude=minutely,hourly&units=metric&appid=".$this->apiKey);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $json = curl_exec($curl);
        curl_close($curl);

        $this->futureWeather = json_decode($json, true);
    }


    /**
     * Get historical 5 day weather information of location
     */
    public function getPreviousWeatherInfo($lat, $lon)
    {
        $result = [];

        // dates for the previous 5 days
        $currentTime = time();
        $previousDay1 = $currentTime - 86400;
        $previousDay2 = $currentTime - (86400 * 2);
        $previousDay3 = $currentTime - (86400 * 3);
        $previousDay4 = $currentTime - (86400 * 4);
        $previousDay5 = $currentTime - (86400 * 5);

        // Build individual requests
        $curl1 = curl_init("https://api.openweathermap.org/data/2.5/onecall/timemachine?lat=".$lat."&lon=".$lon."&dt=".$previousDay1."&units=metric&appid=".$this->apiKey);
        $curl2 = curl_init("https://api.openweathermap.org/data/2.5/onecall/timemachine?lat=".$lat."&lon=".$lon."&dt=".$previousDay2."&units=metric&appid=".$this->apiKey);
        $curl3 = curl_init("https://api.openweathermap.org/data/2.5/onecall/timemachine?lat=".$lat."&lon=".$lon."&dt=".$previousDay3."&units=metric&appid=".$this->apiKey);
        $curl4 = curl_init("https://api.openweathermap.org/data/2.5/onecall/timemachine?lat=".$lat."&lon=".$lon."&dt=".$previousDay4."&units=metric&appid=".$this->apiKey);
        $curl5 = curl_init("https://api.openweathermap.org/data/2.5/onecall/timemachine?lat=".$lat."&lon=".$lon."&dt=".$previousDay5."&units=metric&appid=".$this->apiKey);

        curl_setopt($curl1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl3, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl4, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl5, CURLOPT_RETURNTRANSFER, true);

        // Build multi-curl handle and add all $curls
        $mch = curl_multi_init();
        curl_multi_add_handle($mch, $curl1);
        curl_multi_add_handle($mch, $curl2);
        curl_multi_add_handle($mch, $curl3);
        curl_multi_add_handle($mch, $curl4);
        curl_multi_add_handle($mch, $curl5);

        // Execute all queries simultaneously
        $running = null;
        do {
            curl_multi_exec($mch, $running);
        } while ($running);

        // Close the handles
        curl_multi_remove_handle($mch, $curl1);
        curl_multi_remove_handle($mch, $curl2);
        curl_multi_remove_handle($mch, $curl3);
        curl_multi_remove_handle($mch, $curl4);
        curl_multi_remove_handle($mch, $curl5);
        curl_multi_close($mch);

        // Turn responses into JSON
        for ($i=1; $i < 6; $i++) {
            $json = curl_multi_getcontent(${"curl".$i});
            $jsonDecoded = json_decode($json, true);
            array_push($result, $jsonDecoded);
        }
        $this->previousWeather = $result;
    }

    /**
     * Set API-key to use
     */
    public function setApiKey($key)
    {
        $this->apiKey = $key;
    }

    /**
     * returns the result from getFutureWeatherInfo
     */
    public function getFutureWeatherResult()
    {
        return $this->futureWeather;
    }


    /**
     * returns the result from getPreviousWeatherInfo
     */
    public function getPreviousWeatherResult()
    {
        return $this->previousWeather;
    }


    public function validLocation()
    {
        if (!array_key_exists("cod", $this->futureWeather)) {
            $this->isValidLocation = true;
        }

        return $this->isValidLocation;
    }


    public function resultAsJSON()
    {
        if (array_key_exists("daily", $this->futureWeather) && $this->previousWeather) {
            foreach ($this->futureWeather["daily"] as $res) {
                $day = [
                    "date" => date("Y-m-d", $res["dt"]),
                    "min" => round($res["temp"]["min"]),
                    "max" => round($res["temp"]["max"]),
                    "weather" => $res["weather"][0]["main"]
                ];
                array_push($this->futureWeatherJSON, $day);
            };
    
            foreach ($this->previousWeather as $res) {
                $day = [
                    "date" => date("Y-m-d", $res["current"]["dt"]),
                    "temp" => round($res["current"]["temp"]),
                    "weather" => $res["current"]["weather"][0]["main"]
                ];
                array_push($this->previousWeatherJSON, $day);
            };
        }
    }


    public function getWeatherJSON()
    {
        return $this->futureWeatherJSON;
    }


    public function getPreviousWeatherJSON()
    {
        return $this->previousWeatherJSON;
    }
}
