<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\RegisterDTO;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Events\UserRegistered;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function register(RegisterDTO $dto): array
    {
        $user = $this->userRepository->create([
            'name'     => $dto->name,
            'email'    => $dto->email,
            'phone'    => $dto->phone,
            'password' => $dto->password,
            'role'     => UserRole::Customer,
            'status'   => UserStatus::Active,
        ]);

        event(new UserRegistered($user));

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user'         => $user,
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ];
    }

    public function login(string $email, string $password, ?string $fcmToken = null): array
    {
        $user = $this->userRepository->findByEmail($email);

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (! $user->isActive()) {
            throw ValidationException::withMessages([
                'email' => ['Your account has been suspended or deactivated.'],
            ]);
        }

        if ($fcmToken) {
            $this->userRepository->updateFcmToken($user, $fcmToken);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user'         => $user,
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function sendPasswordResetLink(string $email): string
    {
        $status = Password::sendResetLink(['email' => $email]);

        if ($status !== Password::ResetLinkSent) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return __($status);
    }

    public function resetPassword(string $token, string $email, string $password): string
    {
        $status = Password::reset(
            ['token' => $token, 'email' => $email, 'password' => $password],
            function (User $user, string $password) {
                $user->forceFill(['password' => $password])
                     ->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PasswordReset) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return __($status);
    }
}
