<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function __construct(private readonly Payment $model) {}

    public function findById(int $id): ?Payment
    {
        return $this->model->with('invoice')->find($id);
    }

    public function findByOrderId(string $orderId): ?Payment
    {
        return $this->model->with('invoice')->where('order_id', $orderId)->first();
    }

    public function create(array $data): Payment
    {
        return $this->model->create($data);
    }

    public function update(Payment $payment, array $data): Payment
    {
        $payment->update($data);

        return $payment->fresh();
    }

    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->whereHas('invoice', fn ($q) => $q->where('user_id', $userId))
            ->with('invoice')
            ->latest()
            ->paginate($perPage);
    }
}
