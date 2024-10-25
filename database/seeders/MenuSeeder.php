<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'name' => 'Dashboard',
                'url' => 'admin/dashboard',
            ],
            [
                'name' => '用户管理',
                'url' => 'admin/users',
            ],
            [
                'name' => '角色管理',
                'url' => 'admin/roles',
            ],
            [
                'name' => '权限管理',
                'url' => 'admin/permissions',
            ],
            [
                'name' => '菜单管理',
                'url' => 'admin/menus',
            ],
        ];

        foreach ($menus as $menu) {
            \App\Models\Menu::create($menu);
        }
    }
}
