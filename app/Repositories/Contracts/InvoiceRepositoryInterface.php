<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Invoice;
use App\Enums\InvoiceStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface InvoiceRepositoryInterface
{
    public function findById(int $id): ?Invoice;

    public function findByInvoiceNumber(string $invoiceNumber): ?Invoice;

    public function create(array $data): Invoice;

    public function update(Invoice $invoice, array $data): Invoice;

    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator;

    public function getOverdueInvoices(): Collection;

    public function countByStatus(InvoiceStatus $status): int;

    public function monthlyRevenue(int $months = 12): array;

    public function existsForPeriod(int $userId, string $billingPeriod): bool;
}
