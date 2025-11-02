<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollectionPoint;
use SKAgarwal\GoogleApi\PlacesNew\GooglePlaces;

class CollectionPointController extends Controller
{
    public function map()
    {
        $points = CollectionPoint::all(['name', 'latitude', 'longitude', 'operating_hours', 'address']);
        return view('map', compact('points'));
    }

    public function getNearbyPlaces($latitude, $longitude, $radius = 500)
    {
        $googlePlaces = new GooglePlaces(env('GOOGLE_PLACES_API_KEY'));
        $places = $googlePlaces->nearbySearch($latitude, $longitude, $radius, ['type' => 'recycling_center']);

        return response()->json($places);
    }

    public function getAllPoints()
    {
        return response()->json(CollectionPoint::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'required|string|max:255',
            'operating_hours' => 'nullable|string|max:255',
    ]);

    $point = CollectionPoint::create($validated);

    return response()->json([
        'message' => 'Collection point added successfully!',
        'point' => $point
    ], 201);
}
}