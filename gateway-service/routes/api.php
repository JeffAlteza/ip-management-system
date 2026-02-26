<?php

use App\Http\Controllers\AuthGatewayController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthGatewayController::class, 'login']);
Route::post('/register', [AuthGatewayController::class, 'register']);

Route::middleware('auth.token')->group(function () {
    Route::post('/refresh', [AuthGatewayController::class, 'refresh']);
    Route::post('/logout', [AuthGatewayController::class, 'logout']);
});
