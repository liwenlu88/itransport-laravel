<?php

namespace Database\Factories;

use App\Models\PositionStatus;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\admin>
 */
class AdminFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'contact_tel' => fake()->phoneNumber(),
            'account' => Str::random(10),
            'password' => static::$password ?? static::$password = Hash::make('password'),
            'role_id' => Role::where('id', '!=', 1)->inRandomOrder()->first()->id,
            'position_status_id' => PositionStatus::inRandomOrder()->first()->id,
            'description' => fake()->text(200),
            'remember_token' => Str::random(10),
        ];
    }
}
