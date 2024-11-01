<?php

namespace App\Http\Middleware;

use App\Exceptions\NotAuthorizedException;
use App\Helpers\Helper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * 模块访问权限中间件
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 超级管理员拥有所有权限
        if (Auth::user()->role_id == 1) {
            return $next($request);
        }

        // 获取角色权限
        $permissions = Helper::getRolePermissions(Auth::user()->role_id);

        // 获取当前请求的 URL 并去掉前缀 api/
        $requestedUrl = substr($request->path(), 4);

        // 检查当前 URL 是否匹配用户的权限菜单 (允许子菜单访问 如 /users/create)
        $hasPermission = collect($permissions)->contains(function ($perm) use ($requestedUrl) {
            return $perm->menus->url === $requestedUrl || str_starts_with($requestedUrl, $perm->menus->url . '/');
        });

        if (!$hasPermission) {
            throw new NotAuthorizedException();
        }

        return $next($request);
    }
}
