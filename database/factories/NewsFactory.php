<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\NewsStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'        => fake()->sentence(6),
            'content'      => fake()->paragraphs(5, true),
            'thumbnail'    => null,
            'author'       => fake()->name(),
            'status'       => NewsStatus::Published,
            'published_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'status'       => NewsStatus::Draft,
            'published_at' => null,
        ]);
    }
}
