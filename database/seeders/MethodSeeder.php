<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = ['Create', 'Show', 'Edit', 'Destroy'];

        foreach ($methods as $method) {
            \App\Models\Method::create([
                'name' => $method,
            ]);
        }
    }
}
