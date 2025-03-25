<?php

namespace App\Services;

use App\Models\Address;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GeocodingService
{
    protected $radius = 6371;

    protected function boundingBox($latitude, $longitude, $distance = 10): array
    {
        $maxLat = $latitude + rad2deg($distance / $this->radius);
        $minLat = $latitude - rad2deg($distance / $this->radius);
        $maxLng = $longitude + rad2deg($distance / $this->radius / cos(deg2rad($latitude)));
        $minLng = $longitude - rad2deg($distance / $this->radius / cos(deg2rad($latitude)));

        return compact('maxLat', 'minLat', 'maxLng', 'minLng');
    }

    public function findNearestAddress($latitude, $longitude, $id = 1)
    {
        $cacheKey = "nearest_concessionaire_{$latitude}_{$longitude}";
        
        return Cache::remember($cacheKey, 60, function () use ($latitude, $longitude, $id) {
            $boundingBox = $this->boundingBox($latitude, $longitude);

            return Address::with('store')
                ->select('id', DB::raw("
                    ($this->radius * acos(
                        cos(radians($latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($longitude)) +
                        sin(radians($latitude)) * sin(radians(latitude))
                    )) AS distance
                "))
                ->whereBetween('latitude', [$boundingBox['minLat'], $boundingBox['maxLat']])
                ->whereBetween('longitude', [$boundingBox['minLng'], $boundingBox['maxLng']])
                ->whereHas('store')
                ->orderBy('distance', 'asc')
                ->first();
        });
    }
}
