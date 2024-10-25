<?php

namespace App\Http\Requests\Admin\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
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
            'name' => [
                'required',
                'between:2,10',
                'unique:admins,name,' . $this->route('user')
            ],
            'contact_tel' => [
                'required',
                'regex:/^1[3-9]\d{9}$/',
                'unique:admins,contact_tel,' . $this->route('user')
            ],
            'role_id' => [
                'required',
                'exists:roles,id',
            ],
            'position_status_id' => [
                'required',
                'exists:position_statuses,id',
            ],
            'description' => [
                'max:255',
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '用户名不能为空',
            'name.between' => '用户名长度必须在2-10之间',
            'name.unique' => '用户名已存在',
            'contact_tel.required' => '手机号不能为空',
            'contact_tel.regex' => '手机号格式不正确',
            'contact_tel.unique' => '手机号已存在',
            'role_id.required' => '角色不能为空',
            'role_id.exists' => '角色不存在',
            'position_status_id.required' => '职位状态不能为空',
            'position_status_id.exists' => '职位状态不存在',
            'description.max' => '描述不能超过255个字符',
        ];
    }
}
