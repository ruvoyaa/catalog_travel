<?php

use App\Http\Controllers\Api\PublicTourController;
use Illuminate\Support\Facades\Route;

Route::get('/tours', [PublicTourController::class, 'index']);
Route::get('/tours/{slug}', [PublicTourController::class, 'show']);
