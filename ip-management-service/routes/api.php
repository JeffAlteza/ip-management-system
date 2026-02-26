<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\IpController;
use Illuminate\Support\Facades\Route;

Route::middleware(['internal', 'user.context'])->group(function () {
    Route::get('/ips', [IpController::class, 'index']);
    Route::post('/ips', [IpController::class, 'store']);
    Route::put('/ips/{ip}', [IpController::class, 'update']);
    Route::delete('/ips/{ip}', [IpController::class, 'destroy']);

    Route::get('/audit-logs', [AuditLogController::class, 'index']);
    Route::post('/audit-logs', [AuditLogController::class, 'store']);
});
