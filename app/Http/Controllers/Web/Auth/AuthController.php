<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Auth\LoginRequest;
use App\Models\User;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('account', $request->input('account'))->first();

        $token = $user->createToken(
            'User Token',
            ['user'],
            now()->addDays(7)
        )->plainTextToken;

        return response()->json([
            'code' => 0,
            'message' => 'success',
            'data' => $user,
            'token' => $token
        ]);
    }
}
