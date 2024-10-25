<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'role_id' => 1,
                'menu_id' => 1,
                'method_id' => '1,2,3,4'
            ],
            [
                'role_id' => 1,
                'menu_id' => 2,
                'method_id' => '1,2,3,4'
            ],
            [
                'role_id' => 1,
                'menu_id' => 3,
                'method_id' => '1,2,3,4'
            ],
            [
                'role_id' => 1,
                'menu_id' => 4,
                'method_id' => '1,2,3,4'
            ],
            [
                'role_id' => 1,
                'menu_id' => 5,
                'method_id' => '1,2,3,4'
            ],
            [
                'role_id' => 2,
                'menu_id' => 1,
                'method_id' => '1,2,3'
            ],
            [
                'role_id' => 2,
                'menu_id' => 2,
                'method_id' => '1,2,3'
            ],
            [
                'role_id' => 2,
                'menu_id' => 3,
                'method_id' => '1,2,3'
            ],
            [
                'role_id' => 2,
                'menu_id' => 4,
                'method_id' => '1,2,3'
            ],
            [
                'role_id' => 2,
                'menu_id' => 5,
                'method_id' => '1,2,3'
            ],
        ];

        foreach ($permissions as $permission) {
            \App\Models\Permission::create($permission);
        }
    }
}
