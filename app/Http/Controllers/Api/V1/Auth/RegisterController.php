<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\DTOs\RegisterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    /**
     * @OA\Post(
     *     path="/api/v1/auth/register",
     *     summary="Register a new customer account",
     *     tags={"Authentication"},
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         required={"name","email","phone","password","password_confirmation"},
     *         @OA\Property(property="name", type="string", example="Budi Santoso"),
     *         @OA\Property(property="email", type="string", example="budi@example.com"),
     *         @OA\Property(property="phone", type="string", example="08123456789"),
     *         @OA\Property(property="password", type="string", example="secret123"),
     *         @OA\Property(property="password_confirmation", type="string", example="secret123"),
     *     )),
     *     @OA\Response(response=201, description="Registered successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register(RegisterDTO::fromArray($request->validated()));

        return response()->json([
            'message'      => 'Registration successful. Please verify your email.',
            'user'         => new UserResource($result['user']),
            'access_token' => $result['access_token'],
            'token_type'   => $result['token_type'],
        ], 201);
    }
}
