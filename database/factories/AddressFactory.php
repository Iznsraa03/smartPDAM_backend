<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'      => User::factory(),
            'province'     => fake()->state(),
            'city'         => fake()->city(),
            'district'     => 'Kecamatan ' . fake()->word(),
            'village'      => 'Kelurahan ' . fake()->word(),
            'full_address' => fake()->address(),
            'latitude'     => fake()->latitude(-11, 6),
            'longitude'    => fake()->longitude(95, 141),
            'is_primary'   => false,
        ];
    }
}
