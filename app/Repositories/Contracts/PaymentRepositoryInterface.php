<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PaymentRepositoryInterface
{
    public function findById(int $id): ?Payment;

    public function findByOrderId(string $orderId): ?Payment;

    public function create(array $data): Payment;

    public function update(Payment $payment, array $data): Payment;

    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator;
}
