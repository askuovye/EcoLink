<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\CollectionPointController;

Route::get('/points', [CollectionPointController::class, 'getAllPoints']);
Route::post('/points', [CollectionPointController::class, 'store']);

// ROTA CERTA PARA O FLUTTER
Route::get('/nearby', [CollectionPointController::class, 'nearby']);

Route::get('/map', [CollectionPointController::class, 'map']);

Route::get('/proxy', function (Request $request) {
    $url = $request->query('url');

    if (!$url) {
        return response()->json(['error' => 'URL inválida'], 400);
    }

    $response = Http::get($url);
    return $response->json();
});
