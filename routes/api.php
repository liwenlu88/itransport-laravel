<?php

use App\Http\Controllers\Web\Auth\AuthController;
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
        Route::post('login', [AuthController::class, 'login'])->withoutMiddleware(['auth:sanctum', 'check.type.user']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});
