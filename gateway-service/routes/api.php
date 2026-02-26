<?php

use App\Http\Controllers\AuditLogsGatewayController;
use App\Http\Controllers\AuthGatewayController;
use App\Http\Controllers\IpGatewayController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthGatewayController::class, 'login']);
Route::post('/register', [AuthGatewayController::class, 'register']);

Route::middleware('auth.token')->group(function () {
    Route::post('/refresh', [AuthGatewayController::class, 'refresh']);
    Route::post('/logout', [AuthGatewayController::class, 'logout']);

    Route::get('/ips', [IpGatewayController::class, 'index']);
    Route::post('/ips', [IpGatewayController::class, 'store']);
    Route::put('/ips/{id}', [IpGatewayController::class, 'update']);
    Route::delete('/ips/{id}', [IpGatewayController::class, 'destroy']);

    Route::get('/audit-logs', [AuditLogsGatewayController::class, 'index']);
});
