<?php

use App\Http\Controllers\DriverReviewController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('orders', OrderController::class)->only('store');
    Route::prefix('orders/{order}')->name('orders.')->group(function () {
        Route::post('approve', [OrderController::class, 'approve'])->name('approve');
        Route::post('reject', [OrderController::class, 'reject'])->name('reject');
        Route::post('driver', [OrderController::class, 'driver'])->name('driver');
        Route::post('start', [OrderController::class, 'start'])->name('start');
    });
    Route::apiResource('drivers.review', DriverReviewController::class)
        ->only('store');
});
