<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use Closure;
use Illuminate\Http\Request;
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
        $admin = auth()->user();

        // 获取角色权限
        $permissions = Helper::getRolePermissions($admin->role_id);

        // 获取当前请求的 URL 并去掉前缀 api/
        $requestedUrl = substr($request->path(), 4);

        // 检查当前 URL 是否匹配用户的权限菜单 (允许子菜单访问 如 /users/create)
        $hasPermission = collect($permissions)->contains(function ($perm) use ($requestedUrl) {
            return $perm->menus->url === $requestedUrl || str_starts_with($requestedUrl, $perm->menus->url . '/');
        });

        if (!$hasPermission) {
            return Helper::denyAccess();
        }

        return $next($request);
    }
}
