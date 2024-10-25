<?php

use App\Http\Controllers\Web\Auth\AuthController as UserAuthController;
use App\Http\Controllers\Admin\Auth\AuthController as AdminAuthController;
use Illuminate\Support\Facades\Route;

/** 客户端 */

Route::group([
    'prefix' => 'client',
    'middleware' => [
        'auth:sanctum',
        'check.type.user'
    ]
], function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [UserAuthController::class, 'login'])->withoutMiddleware(['auth:sanctum', 'check.type.user']);
        Route::post('logout', [UserAuthController::class, 'logout']);
    });
});


/** 管理端 */

Route::group([
    'prefix' => 'admin',
    'middleware' => [
        'auth:sanctum',
        'check.type.admin',
        'operation.log', // 记录操作日志
        'check.permission' // 权限验证
    ]
], function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AdminAuthController::class, 'login'])->withoutMiddleware(['auth:sanctum', 'check.type.admin', 'check.permission']);
        Route::post('logout', [AdminAuthController::class, 'logout'])->withoutMiddleware(['check.permission']);
    });
});
