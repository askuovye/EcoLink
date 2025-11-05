<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\CollectionPointController;

Route::get('/points', [CollectionPointController::class, 'getAllPoints']);
Route::get('/nearby/{latitude}/{longitude}/{radius?}', [CollectionPointController::class, 'getNearbyPlaces']);
Route::post('/points', [CollectionPointController::class, 'store']);
Route::get('/map', [CollectionPointController::class, 'map']);
Route::get('/proxy', function (Request $request) {
    $url = $request->query('url');

    if (!$url) {
        return response()->json(['error' => 'URL inválida'], 400);
    }

    $response = Http::get($url);
    return $response->json();
});
