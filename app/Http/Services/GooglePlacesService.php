<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class GooglePlacesService
{
    /**
     * Query Google Places Nearby Search.
     *
     * Returns an array of places with keys: name, lat, lng, address
     *
     * Note: keep your API key in env('GOOGLE_PLACES_API_KEY')
     */
    public function nearby(float $lat, float $lng, int $radius = 5000): array
    {
        $key = env('GOOGLE_PLACES_API_KEY');

        if (empty($key)) {
            throw new RuntimeException('Google Places API key is not configured.');
        }

        $response = Http::get('https://maps.googleapis.com/maps/api/place/nearbysearch/json', [
            'location' => "{$lat},{$lng}",
            'radius'   => $radius,
            'keyword'  => 'reciclagem|descarte|ponto de coleta',
            'language' => 'pt-BR',
            'key'      => $key,
        ]);

        if ($response->failed()) {
            Log::error('Google Places request failed', ['body' => $response->body()]);
            return [];
        }

        $results = $response->json('results', []);

        $output = [];
        foreach ($results as $place) {
            $output[] = [
                'name' => $place['name'] ?? null,
                'lat' => $place['geometry']['location']['lat'] ?? null,
                'lng' => $place['geometry']['location']['lng'] ?? null,
                'address' => $place['vicinity'] ?? ($place['formatted_address'] ?? null),
            ];
        }

        return $output;
    }
}
