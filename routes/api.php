<?php

use App\Http\Controllers\ShortenUrlController;
use Illuminate\Support\Facades\Route;


Route::post('/shorten', [ShortenUrlController::class, 'store']);
Route::get('/{code}', [ShortenUrlController::class, 'redirect']);
