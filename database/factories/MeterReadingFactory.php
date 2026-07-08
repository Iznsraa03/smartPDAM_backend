<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MeterReadingStatus;
use App\Models\WaterMeter;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeterReadingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'water_meter_id'   => WaterMeter::factory(),
            'previous_reading' => 0.0,
            'current_reading'  => fake()->randomFloat(2, 5, 100),
            'meter_photo'      => null,
            'reading_date'     => fake()->dateTimeThisMonth()->format('Y-m-d'),
            'status'           => MeterReadingStatus::Approved,
            'rejection_reason' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => MeterReadingStatus::Pending]);
    }

    public function pendingReview(): static
    {
        return $this->state(fn () => ['status' => MeterReadingStatus::PendingReview]);
    }
}
