<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
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
                'unique:roles,name,' . request()->route('role'),
            ],
            'description' => [
                'max:255'
            ],
            'permissions' => [
                'array'
            ],
            'permissions.*.menu_id' => [
                'exists:menus,id'
            ],
            'permissions.*.method_id' => [
                'array',
                function ($attribute, $value, $fail) {
                    $menuId = request()->input(str_replace('method_id', 'menu_id', $attribute));
                    if ($menuId && empty($value)) {
                        $fail('请选择请求方法');
                    }
                },
            ],
            'permissions.*.method_id.*' => [
                'exists:methods,id'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '请填写角色名称',
            'name.unique' => '角色已存在',
            'description.max' => '描述不能超过255个字符',
            'permissions.*.menu_id.exists' => '菜单不存在',
            'permissions.*.method_id.*.exists' => '请求方法不存在',
        ];
    }
}
