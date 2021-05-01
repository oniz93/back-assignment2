<?php


namespace App\Library;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;


class DownloadGeoJson
{
    public static function getCampaniaPharmacies() {

        Cache::remember('campania_pharmacies_data', 33600, function () {
            try {
                $response = Http::get(env('CAMPANIA_PHARMACIES_GEOJSON_URL'));
                $data = $response->json();
                if(!$data || !isset($data['features'])) {
                    return collect([]);
                }
                return collect($data['features']);
            }
            catch(\Exception $e) {
                return collect([]);
            }

        });
    }
}
