<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VendingApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/products', [VendingApiController::class, 'getEligibleProducts']);

Route::post('/purchase', [VendingApiController::class, 'purchaseSlot']);
