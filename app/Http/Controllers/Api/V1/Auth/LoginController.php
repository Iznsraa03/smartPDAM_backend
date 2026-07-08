<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     summary="Login and receive API token",
     *     tags={"Authentication"},
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         required={"email","password"},
     *         @OA\Property(property="email", type="string", example="budi@example.com"),
     *         @OA\Property(property="password", type="string", example="secret123"),
     *         @OA\Property(property="fcm_token", type="string", nullable=true),
     *     )),
     *     @OA\Response(response=200, description="Login successful"),
     *     @OA\Response(response=422, description="Invalid credentials")
     * )
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login(
            $request->email,
            $request->password,
            $request->fcm_token,
        );

        return response()->json([
            'message'      => 'Login successful.',
            'user'         => new UserResource($result['user']),
            'access_token' => $result['access_token'],
            'token_type'   => $result['token_type'],
        ]);
    }
}
