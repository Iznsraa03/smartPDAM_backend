<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Address\StoreAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $addresses = $request->user()->addresses()->orderByDesc('is_primary')->get();

        return response()->json(AddressResource::collection($addresses));
    }

    public function store(StoreAddressRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Set as primary if it's the user's first address
        if ($request->user()->addresses()->count() === 0) {
            $data['is_primary'] = true;
        }

        // If setting as primary, unset others
        if (! empty($data['is_primary'])) {
            $request->user()->addresses()->update(['is_primary' => false]);
        }

        $address = $request->user()->addresses()->create($data);

        return response()->json([
            'message' => 'Address saved successfully.',
            'address' => new AddressResource($address),
        ], 201);
    }

    public function update(StoreAddressRequest $request, Address $address): JsonResponse
    {
        $this->authorize('update', $address);

        if (! empty($request->validated()['is_primary'])) {
            $request->user()->addresses()->update(['is_primary' => false]);
        }

        $address->update($request->validated());

        return response()->json([
            'message' => 'Address updated successfully.',
            'address' => new AddressResource($address->fresh()),
        ]);
    }

    public function destroy(Request $request, Address $address): JsonResponse
    {
        $this->authorize('delete', $address);
        $address->delete();

        return response()->json(['message' => 'Address deleted.']);
    }
}
