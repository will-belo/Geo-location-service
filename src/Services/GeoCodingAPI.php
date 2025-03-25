<?php

namespace GeoLocationService\Services;

use Illuminate\Support\Facades\Http;

class GeoCodingAPI
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = config('geolocation.google_api_key');
    }

    public function getCoordinates(string $address): ?array
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key=" . $this->apiKey;

        $data = Http::get($url)->json();
        
        return $data['status'] === 'OK' ? $data['results'][0]['geometry']['location'] : null;
    }
}
