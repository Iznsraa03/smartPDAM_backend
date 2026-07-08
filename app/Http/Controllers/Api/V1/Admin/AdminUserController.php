<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function __construct(private readonly UserRepositoryInterface $userRepository) {}

    public function index(Request $request): JsonResponse
    {
        $users = $this->userRepository->paginate(15, $request->only(['role', 'status', 'search']));

        return response()->json(UserResource::collection($users)->response()->getData(true));
    }

    public function show(User $user): JsonResponse
    {
        return response()->json(new UserResource($user->load('addresses', 'waterMeters')));
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'name'   => ['sometimes', 'string', 'max:255'],
            'status' => ['sometimes', 'string'],
            'role'   => ['sometimes', 'string'],
        ]);

        $updated = $this->userRepository->update($user, $data);

        return response()->json([
            'message' => 'User updated.',
            'user'    => new UserResource($updated),
        ]);
    }
}
