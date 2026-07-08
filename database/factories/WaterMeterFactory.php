<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MeterType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WaterMeterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'      => User::factory(),
            'meter_number' => strtoupper(fake()->bothify('??###???###')),
            'meter_type'   => fake()->randomElement(MeterType::cases()),
            'is_active'    => true,
        ];
    }
}
