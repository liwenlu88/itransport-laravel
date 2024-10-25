<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\NotAuthorizedException;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\Admin\Role\RoleResourceCollection;
use App\Http\Resources\Admin\Role\ShowRoleResource;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{

    /**
     * 只有超级管理员账户可以访问该控制器
     */
    public function __construct()
    {
        Auth::user()->role_id != 1 && throw new NotAuthorizedException();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $prePage = $request->input('par_page', 10);
        $page = $request->input('page', 1);

        $name = $request->input('name');

        $roleQuery = Role::query();

        if (!empty($name)) {
            $roleQuery->where('name', 'like', "%$name%");
        }

        $roles = $roleQuery->paginate($prePage, ['*'], 'page', $page);

        return response()->json([
            'code' => 0,
            'mssage' => 'success',
            'data' => new RoleResourceCollection($roles)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json([
            'code' => 0,
            'mssage' => 'success',
            'data' => [
                'menu_list' => Helper::getMenuList(),
                'method_list' => Helper::getMethodList()
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Helper::authorizeAndRespond('create', Role::class, function () use ($request) {
            // 验证表单数据
            $validateDate = Helper::requestValidation($request, StoreRoleRequest::class);

            $roles = DB::transaction(function () use ($request, $validateDate) {
                // 创建角色
                $role = new Role();

                $role->fill($validateDate);
                $role->save();

                // 为角色赋予权限
                if ($validateDate['permissions'] && is_array($validateDate['permissions'])) {
                    foreach ($validateDate['permissions'] as $permissionData) {
                        $formattedMethodId = implode(',', $permissionData['method_id']);;

                        $permission = new Permission();
                        $permission->role_id = $role->id;
                        $permission->menu_id = $permissionData['menu_id'];
                        $permission->method_id = $formattedMethodId;

                        $permission->save();
                    }
                }

                return $role;
            });

            // 刷新角色权限缓存
            Helper::refreshRolePermissions($roles->id);

            return response()->json([
                'code' => 0,
                'mssage' => 'success',
                'data' => [
                    'roles' => new ShowRoleResource($roles)
                ]
            ]);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Helper::authorizeAndRespond('show', Role::class, function () use ($id) {
            $role = Role::find($id);

            if (empty($role)) {
                return Helper::dataNotFound('角色不存在或已删除');
            }

            return response()->json([
                'code' => 0,
                'mssage' => 'success',
                'data' => [
                    'roles' => new ShowRoleResource($role)
                ]
            ]);
        });
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Helper::authorizeAndRespond('edit', Role::class, function () use ($id) {
            $role = Role::find($id);

            if (empty($role)) {
                return Helper::dataNotFound('角色不存在或已删除');
            }

            return response()->json([
                'code' => 0,
                'mssage' => 'success',
                'data' => [
                    'roles' => new ShowRoleResource($role),
                    'menu_list' => Helper::getMenuList(),
                    'method_list' => Helper::getMethodList()
                ]
            ]);
        });
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return Helper::authorizeAndRespond('edit', Role::class, function () use ($request, $id) {
            $role = Role::find($id);

            if (empty($role)) {
                return Helper::dataNotFound('角色不存在或已删除');
            }

            // 验证表单数据
            $validateDate = Helper::requestValidation($request, UpdateRoleRequest::class);

            $roles = DB::transaction(function () use ($request, $validateDate, $role) {
                // 更新角色
                $role->fill($validateDate);
                $role->save();

                // 更新角色权限
                if ($validateDate['permissions'] && is_array($validateDate['permissions'])) {
                    foreach ($validateDate['permissions'] as $permissionData) {
                        $formattedMethodId = implode(',', $permissionData['method_id']);

                        // 查找能够匹配的权限
                        $permissions = Permission::where('role_id', $role->id)
                            ->where('menu_id', $permissionData['menu_id'])
                            ->first();

                        // 如果没有找到，则创建新的权限
                        if (empty($permissions)) {
                            $permissions = new Permission();
                            $permissions->role_id = $role->id;
                            $permissions->menu_id = $permissionData['menu_id'];
                        }
                        $permissions->method_id = $formattedMethodId;

                        $permissions->save();
                    }
                }

                return $role;
            });

            // 刷新角色权限缓存
            Helper::refreshRolePermissions($roles->id);

            return response()->json([
                'code' => 0,
                'mssage' => 'success',
                'data' => [
                    'roles' => new ShowRoleResource($roles)
                ]
            ]);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Helper::authorizeAndRespond('destroy', Role::class, function () use ($id) {
            $role = Role::find($id);

            if (empty($role)) {
                return Helper::dataNotFound('角色不存在或已删除');
            }

            DB::transaction(function () use ($role) {
                // 匹配该角色的账户 role_id 置空
                $admins = $role->admins()->get();

                foreach ($admins as $admin) {
                    $admin->role_id = null;
                    $admin->save();
                }

                // 删除角色权限
                $role->permissions()->delete();

                // 删除角色
                $role->delete();
            });

            return response()->json([
                'code' => 0,
                'mssage' => 'success'
            ]);
        });
    }
}
