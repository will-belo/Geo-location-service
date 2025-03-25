<?php

return [
    'radius' => env('GEOLOCATION_RADIUS', 6371),
    'cache_duration' => env('GEOLOCATION_CACHE_DURATION', 60),
    'google_api_key' => env('GEOLOCATION_GOOGLE_API_KEY', '<YOUR_GoogleAPIKey_HERE>'),
    'address_model' => env('GEOLOCATION_ADDRESS_MODEL', 'App\Models\Address'),
];