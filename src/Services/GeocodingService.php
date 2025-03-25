<?php

namespace GeoLocationService\Services;

use GeoLocationService\Contracts\AddressModelInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GeocodingService
{
    protected $radius;
    protected $addressModel;

    public function __construct()
    {
        $this->radius = config('geolocation.radius');
        $addressModelClass = config('geolocation.address_model');

        if (!class_exists($addressModelClass)) {
            throw new \Exception("A model de endereço {$addressModelClass} não existe.");
        }
        
        $this->addressModel = new $addressModelClass();
    }

    protected function boundingBox(float $latitude, float $longitude, int $distance = 10): array
    {
        $maxLat = $latitude + rad2deg($distance / $this->radius);
        $minLat = $latitude - rad2deg($distance / $this->radius);
        $maxLng = $longitude + rad2deg($distance / $this->radius / cos(deg2rad($latitude)));
        $minLng = $longitude - rad2deg($distance / $this->radius / cos(deg2rad($latitude)));

        return compact('maxLat', 'minLat', 'maxLng', 'minLng');
    }

    public function findNearestAddress(float $latitude, float $longitude, int $id = 1): ?Model
    {
        $cacheDuration = config('geolocation.cache_duration');
        $cacheKey = "nearest_concessionaire_{$latitude}_{$longitude}";
        
        return Cache::remember($cacheKey, $cacheDuration, function () use ($latitude, $longitude, $id) {
            $boundingBox = $this->boundingBox($latitude, $longitude);

            return $this->addressModel::with('relatedEntity')
                ->select('id', DB::raw("
                    ($this->radius * acos(
                        cos(radians($latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($longitude)) +
                        sin(radians($latitude)) * sin(radians(latitude))
                    )) AS distance
                "))
                ->whereBetween('latitude', [$boundingBox['minLat'], $boundingBox['maxLat']])
                ->whereBetween('longitude', [$boundingBox['minLng'], $boundingBox['maxLng']])
                ->whereHas('relatedEntity')
                ->orderBy('distance', 'asc')
                ->first();
        });
    }
}
