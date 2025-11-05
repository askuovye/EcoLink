<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollectionPoint;
use Illuminate\Support\Facades\Http;

class CollectionPointController extends Controller
{
    public function map()
    {
        $points = CollectionPoint::all(['name', 'latitude', 'longitude', 'operating_hours', 'address']);
        return view('map', compact('points'));
    }

    public function getAllPoints()
    {
        return response()->json(CollectionPoint::all());
    }

    public function store(Request $request)
    {
        $point = CollectionPoint::create($request->all());
        return response()->json($point);
    }

    public function getNearbyPlaces($latitude, $longitude, $radius = 3000)
    {
        $apiKey = env('GOOGLE_PLACES_API_KEY');
        $query = urlencode('ponto de coleta de lixo');

        $url = "https://maps.googleapis.com/maps/api/place/textsearch/json?query={$query}&location={$latitude},{$longitude}&radius={$radius}&key={$apiKey}";

        $response = Http::get($url);
        $places = $response->json()['results'] ?? [];

        $points = [];

        foreach ($places as $place) {
            $points[] = [
                'name' => $place['name'],
                'latitude' => $place['geometry']['location']['lat'],
                'longitude' => $place['geometry']['location']['lng'],
            ];
        }

        // Salva no banco
        foreach ($points as $p) {
            CollectionPoint::updateOrCreate(
                ['name' => $p['name']],
                ['latitude' => $p['latitude'], 'longitude' => $p['longitude']]
            );
        }

        return response()->json($points);
    }
}
