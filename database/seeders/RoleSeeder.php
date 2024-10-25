<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['超级管理员', '管理员', '业务', '财务', '车队长'];

        foreach ($roles as $role) {
            \App\Models\Role::create([
                'name' => $role,
            ]);
        }
    }
}
