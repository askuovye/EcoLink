<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CollectionPointController;

Route::get('/map', [CollectionPointController::class, 'map'])->name('map');
