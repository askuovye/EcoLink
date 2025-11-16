<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\CollectionPointController;
use App\Http\Controllers\Api\CollectionPointApiController;


Route::middleware('api')->group(function () {

    Route::get('/points', [CollectionPointApiController::class, 'all']);

    Route::post('/points', [CollectionPointController::class, 'store']); 

    Route::get('/nearby', [CollectionPointApiController::class, 'nearby']);

    Route::get('/map', [CollectionPointController::class, 'map']);

    Route::get('/proxy', function (Request $request) {
        $url = $request->query('url');

        if (!$url) {
            return response()->json(['error' => 'URL inválida'], 400);
        }

        $response = Http::get($url);
        return $response->json();
    });
});
