<?php

namespace App\Http\Requests\Admin\Web\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'account' => [
                'required',
                'exists:users,account'
            ],
            'password' => [
                'required',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'account.required' => '账号不可为空，请填写账号',
            'account.exists' => '账号不存在，请联系管理员',
            'password.required' => '密码不可为空，请填写密码',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $account = $this->input('account');
            $password = $this->input('password');

            // 查询用户信息
            $user = User::where('account', $account)->first();

            // 验证密码是否正确
            if ($user && !Hash::check($password, $user->password)) {
                $validator->errors()->add('password', '密码输入错误');
            }
        });
    }
}
