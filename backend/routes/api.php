<?php

use App\Http\Controllers\Api\V1\DriverController;
use App\Http\Controllers\Api\V1\LoginController;
use App\Http\Controllers\Api\V1\TripController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('login/verify', [LoginController::class, 'verify']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('user', function (Request $request) {
            return $request->user();
        });

        Route::controller(DriverController::class)->group(function () {
            Route::get('driver', 'show');
            Route::post('driver', 'update');
        });

        Route::controller(TripController::class)->group(function () {
            Route::post('trip', 'store');
            Route::get('trip/{trip}', 'show');

            Route::get('trip/{trip}/accept', 'accept');
            Route::get('trip/{trip}/start', 'start');
            Route::get('trip/{trip}/end', 'end');
            Route::get('trip/{trip}/location', 'location');
        });
    });
});
