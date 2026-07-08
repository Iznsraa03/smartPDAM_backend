<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::firstOrCreate(
            ['email' => 'superadmin@smartpdam.id'],
            [
                'name'              => 'Super Admin',
                'phone'             => '081000000001',
                'password'          => Hash::make('password'),
                'role'              => UserRole::SuperAdmin,
                'status'            => UserStatus::Active,
                'email_verified_at' => now(),
            ]
        );

        // PDAM Admin
        User::firstOrCreate(
            ['email' => 'admin@smartpdam.id'],
            [
                'name'              => 'PDAM Admin',
                'phone'             => '081000000002',
                'password'          => Hash::make('password'),
                'role'              => UserRole::PdamAdmin,
                'status'            => UserStatus::Active,
                'email_verified_at' => now(),
            ]
        );

        // Sample Customers
        User::factory()->count(10)->create();
    }
}
