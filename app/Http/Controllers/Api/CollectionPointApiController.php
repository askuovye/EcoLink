<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\CollectionPointService;

class CollectionPointApiController extends Controller
{
    private CollectionPointService $service;

    public function __construct(CollectionPointService $service)
    {
        $this->service = $service;
    }

    public function nearby(Request $request)
    {
        $validated = $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        return response()->json(
            $this->service->getNearbyPoints($validated['lat'], $validated['lng'])
        );
    }

    public function all()
    {
        return response()->json($this->service->all());
    }
}
