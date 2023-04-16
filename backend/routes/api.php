<?php

use App\Http\Controllers\Api\V1\DriverController;
use App\Http\Controllers\Api\V1\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('login/verify', [LoginController::class, 'verify']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('user', function () {
            return auth()->user();
        });

        Route::get('driver', [DriverController::class, 'show']);
        Route::post('driver', [DriverController::class, 'update']);
    });
});
