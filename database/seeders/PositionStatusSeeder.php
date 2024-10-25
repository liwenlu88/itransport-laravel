<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positionStatuses = ['在职', '离职', '冻结'];

        foreach ($positionStatuses as $positionStatus) {
            \App\Models\PositionStatus::create(['name' => $positionStatus]);
        }
    }
}
