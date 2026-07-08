<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Authentication', function () {

    it('registers a new customer successfully', function () {
        $response = $this->postJson('/api/v1/auth/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@smartpdam.id',
            'phone'                 => '081234567890',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertCreated()
                 ->assertJsonStructure(['message', 'user', 'access_token', 'token_type']);

        $this->assertDatabaseHas('users', ['email' => 'test@smartpdam.id']);
    });

    it('fails registration with duplicate email', function () {
        User::factory()->create(['email' => 'existing@smartpdam.id']);

        $response = $this->postJson('/api/v1/auth/register', [
            'name'                  => 'Another User',
            'email'                 => 'existing@smartpdam.id',
            'phone'                 => '082222222222',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertUnprocessable()
                 ->assertJsonValidationErrors(['email']);
    });

    it('logs in with valid credentials', function () {
        $user = User::factory()->create(['email' => 'login@smartpdam.id']);

        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => 'login@smartpdam.id',
            'password' => 'password',
        ]);

        $response->assertOk()
                 ->assertJsonStructure(['access_token', 'user']);
    });

    it('rejects login with wrong password', function () {
        User::factory()->create(['email' => 'wrong@smartpdam.id']);

        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => 'wrong@smartpdam.id',
            'password' => 'wrongpassword',
        ]);

        $response->assertUnprocessable();
    });

    it('logs out successfully', function () {
        $user  = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $this->withHeader('Authorization', "Bearer {$token}")
             ->postJson('/api/v1/auth/logout')
             ->assertOk()
             ->assertJson(['message' => 'Logged out successfully.']);
    });

    it('returns 401 for unauthenticated profile access', function () {
        $this->getJson('/api/v1/profile')->assertUnauthorized();
    });

    it('returns profile for authenticated user', function () {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
             ->getJson('/api/v1/profile')
             ->assertOk()
             ->assertJsonStructure(['id', 'name', 'email', 'role']);
    });
});
