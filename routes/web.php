<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CollectionPointController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/map', [CollectionPointController::class, 'map'])->name('map');

Route::post('/points/{point}/verify', [CollectionPointController::class, 'verify'])->name('points.verify');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('points', CollectionPointController::class);
});

require __DIR__.'/auth.php';