<?php

use App\Http\Controllers\ApprovalController;
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
    Route::post('orders/{order}/approve', [ApprovalController::class, 'approve'])
        ->name('orders.approve');
    Route::post('orders/{order}/reject', [ApprovalController::class, 'reject'])
        ->name('orders.reject');
    Route::apiResource('drivers.review', DriverReviewController::class)
        ->only('store');
});
