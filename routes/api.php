<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CollectionPointController;

Route::get('/points', [CollectionPointController::class, 'getAllPoints']);
Route::get('/nearby/{latitude}/{longitude}/{radius?}', [CollectionPointController::class, 'getNearbyPlaces']);
Route::post('/points', [CollectionPointController::class, 'store']);
Route::get('/map', [CollectionPointController::class, 'map']);