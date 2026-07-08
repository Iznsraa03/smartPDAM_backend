<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(private readonly UserRepositoryInterface $userRepository) {}

    public function show(Request $request): JsonResponse
    {
        return response()->json(new UserResource($request->user()->load('addresses')));
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $this->userRepository->update($request->user(), $request->validated());

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user'    => new UserResource($user),
        ]);
    }
}
