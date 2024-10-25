<?php

use App\Http\Controllers\Web\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/** 客户端 */

Route::group([
    'prefix' => 'client',
    'auth:sanctum'
], function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
    });
});
