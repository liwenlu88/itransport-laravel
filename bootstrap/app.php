<?php

use App\Http\Middleware\CheckAdminToken;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\checkUserToken;
use App\Http\Middleware\OperationLog;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'check.type.user' => CheckUserToken::class,
            'check.type.admin' => CheckAdminToken::class,
            'operation.log' => OperationLog::class, // 记录操作日志
            'check.permission' => CheckPermission::class, // 权限验证
        ]);
        $middleware->append([
            //
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'code' => 401,
                'message' => '账户未经授权，请先登录。',
            ], 401);
        });
    })->create();
