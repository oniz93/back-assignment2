<?php

namespace App\Http\Controllers;

use AvtoDev\JsonRpc\Requests\RequestInterface;
use Illuminate\Support\Facades\Cache;
use AvtoDev\JsonRpc\Http\Controllers\RpcController;

class SearchController extends RpcController
{
    public function getNearestPharmacy(RequestInterface $request) {
        // Take all params
        $params = $request->getParams();
        $latitude_user = $params->currentLocation->latitude;
        $longitude_user = $params->currentLocation->longitude;
        $range = $params->range;
        $limit = $params->limit;

        // Get pharmacies from cache
        $pharmacies = Cache::get('campania_pharmacies_data');


        // Cycle all the pharmacies and calculate the distance between user points and pharmacy points
        $pharmacies = $pharmacies->map(function($row) use ($latitude_user, $longitude_user){
            $lon_row = $row['geometry']['coordinates'][0];
            $lat_row = $row['geometry']['coordinates'][1];

            // $distance = (3959 * acos( cos($deg_lat_user) * cos($lat_row) * cos($deg_lon_user - $lon_row ) + sin($deg_lat_user) * sin($lat_row)))*1.609*1000;
            $distance = self::vincentyGreatCircleDistance($latitude_user, $longitude_user, $lat_row, $lon_row);
            return [
                "distance" => (int) $distance,
                "name" => $row['properties']['Descrizione']??'',
                "location" => [
                    "latitude" => $row['geometry']['coordinates'][1],
                    "longitude" => $row['geometry']['coordinates'][0],
                ]
            ];
        });

        // Extract only the pharmacy in range - sort by distance - take only the N requested by limit
        $nearPoints = $pharmacies
                        ->where('distance', '<=', $range)
                        ->sortBy('distance')
                        ->take($limit)
                        ->toArray();
        $nearPoints = array_values($nearPoints);

        return $nearPoints;

    }

    /**
     * Calculates the great-circle distance between two points, with
     * the Vincenty formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     * @return float Distance between points in [m] (same as earthRadius)
     */
    public static function vincentyGreatCircleDistance(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }
}
