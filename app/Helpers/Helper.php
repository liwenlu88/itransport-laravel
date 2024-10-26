<?php

namespace App\Helpers;

use App\Exceptions\NotAuthorizedException;
use App\Models\Menu;
use App\Models\Method;
use App\Models\PositionStatus;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redis;

class Helper
{
    /**
     * Policy 授权检查
     *
     * @param string $method
     * @return bool
     */
    public static function authorize(string $method): bool
    {
        // 超级管理员拥有所有权限
        if (Auth::user()->role_id == 1) {
            return true;
        }

        // 获取当前用户的权限
        $permissions = Auth::user()->roles->permissions;

        // 检查是否有对应的权限
        return $permissions->contains(function ($perm) use ($method) {
            return $perm->methods()->contains('name', ucfirst($method));
        });
    }

    /**
     * Policy 授权权限检查并返回成功回调函数的响应
     *
     * @param string $action
     * @param string $modelClass
     * @param callable $successCallback
     * @return mixed
     */
    public static function authorizeAndRespond(string $action, string $modelClass, callable $successCallback): mixed
    {
        $response = Gate::inspect($action, $modelClass);

        if ($response->allowed()) { // 授权成功
            return $successCallback();
        } else { // 授权失败
            throw new NotAuthorizedException();
        }
    }

    /**
     * 验证表单数据并返回验证后的数据
     *
     * @param Request $request
     * @param string $formRequestClass
     * @return array
     *
     */
    public static function requestValidation(Request $request, string $formRequestClass): array
    {
        // 使用服务容器解析 FormRequest 实例
        $formRequest = app($formRequestClass);
        // 将原始请求数据合并到 FormRequest 实例中
        $formRequest->merge($request->all());
        // 手动触发验证
        $formRequest->validateResolved();

        // 返回已验证的数据
        return $formRequest->validated();
    }

    /**
     * 将原数据添加到请求头 X-Original-Data -- 用于日志记录
     *
     * @param Request $request
     * @param Model $model
     * @return void
     *
     */
    public static function setOriginalDataToRequestHeader(Request $request, Model $model): void
    {
        // 获取原数据
        $originalData = $model->getAttributes();

        // 将原数据添加到请求头
        $request->headers->set('X-Original-Data', json_encode($originalData));
    }

    /**
     * Redis 中查找角色权限，不存在则从数据库中查找并缓存
     *
     * @param int $roleId
     * @return mixed
     */
    public static function getRolePermissions(int $roleId): mixed
    {
        // 定义 Redis 键，角色 ID
        $cacheKey = 'roles_permissions_' . $roleId;

        // 从 Redis 获取权限
        $permissions = Redis::get($cacheKey);

        // 如果 Redis 缓存中没有权限数据，从数据库获取并创建 24 小时的缓存
        if (empty($permissions)) {
            $permissions = Role::find($roleId)->permissions()->with('menus')->get()->toJson();
            Redis::setex($cacheKey, 60 * 60 * 24, $permissions);
        }

        // 解码 JSON 格式的权限数据
        return json_decode($permissions);
    }

    /**
     * 刷新 Redis 角色权限缓存
     *
     * @param int $roleId
     * @return void
     */
    public static function refreshRolePermissions(int $roleId): void
    {
        // 定义 Redis 键，角色 ID
        $cacheKey = 'roles_permissions_' . $roleId;

        // 从数据库获取权限并创建 24 小时的缓存
        $permissions = Role::find($roleId)->permissions()->with('menus')->get()->toJson();
        Redis::setex($cacheKey, 60 * 60 * 24, $permissions);
    }

    /**
     * 获取角色列表 -- 不包含超级管理员
     *
     * @return mixed
     *
     */
    public static function getRoleList(): mixed
    {
        return Role::whereNot('id', 1)->select('id', 'name')->get();
    }

    /**
     * 获取菜单列表
     *
     * @return mixed
     *
     */
    public static function getMenuList(): mixed
    {
        return Menu::select('id', 'name', 'url')->get();
    }

    /**
     * 获取请求方法列表
     *
     * @return mixed
     */
    public static function getMethodList(): mixed
    {
        return Method::select('id', 'name')->get();
    }

    /**
     * 获取职位状态列表
     *
     * @return mixed
     *
     */
    public static function getPositionStatusList(): mixed
    {
        return PositionStatus::select('id', 'name')->get();
    }
}
