<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class PasswordResetController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    public function sendResetLink(ForgotPasswordRequest $request): JsonResponse
    {
        $status = $this->authService->sendPasswordResetLink($request->email);

        return response()->json(['message' => $status]);
    }

    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        $status = $this->authService->resetPassword(
            $request->token,
            $request->email,
            $request->password,
        );

        return response()->json(['message' => $status]);
    }
}
