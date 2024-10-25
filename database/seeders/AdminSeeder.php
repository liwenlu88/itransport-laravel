<?php

namespace Database\Seeders;

use App\Models\admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::factory(2000)->create();

        $admin = Admin::find(1);
        $admin->name = 'Super Admin';
        $admin->account = 'admin';
        $admin->role_id = 1;
        $admin->position_status_id = 1;
        $admin->save();
    }
}
