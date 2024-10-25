<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(20)->create();

        $user = User::find(1);
        $user->name = 'User';
        $user->account = 'user';
        $user->position_status_id = 1;
        $user->save();
    }
}
