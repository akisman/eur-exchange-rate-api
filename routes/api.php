<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExchangeRateController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/rates', [ExchangeRateController::class, 'index']);
Route::get('/rates/{exchange_rate}', [ExchangeRateController::class, 'show']);

