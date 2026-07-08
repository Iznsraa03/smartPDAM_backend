<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'phone'             => fake()->unique()->numerify('08##########'),
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'role'              => UserRole::Customer,
            'status'            => UserStatus::Active,
            'remember_token'    => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => ['role' => UserRole::PdamAdmin]);
    }

    public function superAdmin(): static
    {
        return $this->state(fn () => ['role' => UserRole::SuperAdmin]);
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }
}
