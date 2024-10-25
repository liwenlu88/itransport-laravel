<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginRequest;
use App\Models\Admin;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $admin = Admin::where('account', $request->input('account'))->first();

        $token = $admin->createToken(
            'Admin Token',
            ['admin'],
            now()->addDays(7)
        )->plainTextToken;

        return response()->json([
            'code' => 0,
            'message' => 'success',
            'data' => $admin,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'code' => 0,
            'message' => 'success'
        ]);
    }
}
