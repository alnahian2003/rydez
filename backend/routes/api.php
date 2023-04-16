<?php

use App\Http\Controllers\Api\V1\LoginController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('login/verify', [LoginController::class, 'verify']);
});
