<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Exceptions\DataNotFoundException;
use App\Exceptions\NotAuthorizedException;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\StoreAdminRequest;
use App\Http\Requests\Admin\Auth\UpdateAdminRequest;
use App\Http\Resources\Admin\Auth\AdminResource;
use App\Http\Resources\Admin\Auth\AdminResourceCollection;
use App\Models\admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function options()
    {
        return response()->json([
            'code' => 0,
            'message' => 'success',
            'data' => [
                'role_list' => Helper::getRoleList(),
                'position_status_list' => Helper::getPositionStatusList()
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageSize = $request->input('page_size', 10);
        $page = $request->input('page', 1);

        $name = $request->input('name'); // 用户名
        $contact_tel = $request->input('contact_tel'); // 联系电话
        $roleId = $request->input('roleId'); // 角色ID
        $positionStatusId = $request->input('positionStatusId'); // 职位状态ID

        $adminQuery = Admin::query();

        if (Auth::user()->role_id != 1) {
            $adminQuery->whereNot('id', Auth::user()->id);
        }

        if (!empty($name)) {
            $adminQuery->where('name', 'like', "%$name%");
        }

        if (!empty($contact_tel)) {
            $adminQuery->where('contact_tel', 'like', "%$contact_tel%");
        }

        if (!empty($roleId)) {
            $adminQuery->where('role_id', $roleId);
        }

        if (!empty($positionStatusId)) {
            $adminQuery->where('position_status_id', $positionStatusId);
        }

        $admins = $adminQuery->paginate($pageSize, ['*'], 'page', $page);

        return response()->json([
            'code' => 0,
            'message' => 'success',
            'data' => new AdminResourceCollection($admins)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Helper::authorizeAndRespond('create', Admin::class, function () {
            return response()->json([
                'code' => 0,
                'message' => 'success',
                'data' => [
                    'role_list' => Helper::getRoleList(),
                    'position_status_list' => Helper::getPositionStatusList()
                ]
            ]);
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Helper::authorizeAndRespond('create', Admin::class, function () use ($request) {
            // 验证表单数据
            $validatedData = Helper::requestValidation($request, StoreAdminRequest::class);

            // 保存用户信息
            $admin = new Admin();
            $admin->fill($validatedData);
            $admin->save();

            return response()->json([
                'code' => 0,
                'message' => 'success',
                'data' => [
                    'user' => new AdminResource($admin)
                ]
            ]);
        });
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Helper::authorizeAndRespond('show', Admin::class, function () use ($id) {
            $admin = Admin::find($id);

            if (empty($admin)) {
                throw new DataNotFoundException();
            }

            if ($admin->role_id == 1 && Auth::user()->id != $id) {
                throw new NotAuthorizedException();
            }

            return response()->json([
                'code' => 0,
                'message' => 'success',
                'data' => [
                    'user' => new AdminResource($admin)
                ]
            ]);
        });
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Helper::authorizeAndRespond('edit', Admin::class, function () use ($id) {
            $admin = Admin::find($id);

            if (empty($admin)) {
                throw new DataNotFoundException();
            }
            // 只有超级管理员可以编辑自己的信息
            if ($admin->role_id == 1 && Auth::user()->id != $id) {
                throw new NotAuthorizedException();
            }

            return response()->json([
                'code' => 0,
                'message' => 'success',
                'data' => [
                    'user' => new AdminResource($admin),
                    'role_list' => Helper::getRoleList(),
                    'position_status_list' => Helper::getPositionStatusList()
                ]
            ]);
        });
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return Helper::authorizeAndRespond('edit', Admin::class, function () use ($request, $id) {
            $admin = Admin::find($id);

            if (empty($admin)) {
                throw new DataNotFoundException();
            }

            // 记录原始数据
            Helper::setOriginalDataToRequestHeader($request, $admin);

            $validatedData = Helper::requestValidation($request, UpdateAdminRequest::class);

            // 只有超级管理员自己可以编辑自己的信息
            if ($admin->role_id == 1 && Auth::user()->id != $admin->id) {
                throw new NotAuthorizedException();
            }

            $admin->fill($validatedData);
            $admin->save();

            return response()->json([
                'code' => 0,
                'message' => 'success',
            ]);
        });
    }

    public function destroy(string $id)
    {
        return Helper::authorizeAndRespond('destroy', Admin::class, function () use ($id) {
            $admin = Admin::find($id);

            if (empty($admin)) {
                throw new DataNotFoundException();
            }

            // 超级管理员账户不能删除
            if ($admin->role_id == 1) {
                throw new NotAuthorizedException();
            }

            $admin->delete();

            return response()->json([
                'code' => 0,
                'message' => 'success',
            ]);
        });
    }
}
