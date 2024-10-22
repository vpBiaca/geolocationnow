<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;

// Handle POST request to store location data
Route::post('/store-location', [LocationController::class, 'store']);

// Handle GET request to show the location tracking view
Route::get('/', function () {
    return view('mylocation');
});
