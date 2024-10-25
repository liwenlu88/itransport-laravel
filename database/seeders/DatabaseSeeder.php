<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PositionStatusSeeder::class, // 职位状态
            UserSeeder::class, // 用户
            MenuSeeder::class, // 菜单
            MethodSeeder::class, // 请求方法
            PermissionSeeder::class, // 权限
            RoleSeeder::class, // 角色
            AdminSeeder::class, // 管理员
        ]);
    }
}
