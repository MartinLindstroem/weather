<?php

namespace Marty\Weather;

class LocationLookup
{

    private $result;

    public function searchByCoords($lat, $lon)
    {
        $curl = curl_init("https://nominatim.openstreetmap.org/reverse?lat=".$lat."&lon=".$lon."&format=json&email=martinllindstroem@gmail.com");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $json = curl_exec($curl);
        curl_close($curl);

        $this->result = json_decode($json, true);
    }

    public function getResult()
    {
        return $this->result;
    }
}
