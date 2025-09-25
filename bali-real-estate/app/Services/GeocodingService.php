<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodingService {
    public function geocode(?string $address): ?array {
        $key = config('google.maps.api_key');
        if(!$address || !$key) return null;
        $res = Http::timeout(10)->get('https://maps.googleapis.com/maps/api/geocode/json',[
            'address'=>$address,'key'=>$key
        ]);
        if(!$res->ok()) return null;
        $j=$res->json();
        
        // Log geocoding errors for debugging
        if(($j['status']??'') !== 'OK') {
            Log::info('Geocoding failed', [
                'address' => $address,
                'status' => $j['status'] ?? 'unknown',
                'error' => $j['error_message'] ?? 'no error message'
            ]);
            return null;
        }
        
        $loc=$j['results'][0]['geometry']['location']??null;
        return $loc? ['lat'=>$loc['lat'],'lng'=>$loc['lng']] : null;
    }
}