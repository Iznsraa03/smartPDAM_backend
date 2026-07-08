<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Repositories\Contracts\InvoiceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function __construct(private readonly Invoice $model) {}

    public function findById(int $id): ?Invoice
    {
        return $this->model->with(['user', 'payment', 'meterReading'])->find($id);
    }

    public function findByInvoiceNumber(string $invoiceNumber): ?Invoice
    {
        return $this->model->where('invoice_number', $invoiceNumber)->first();
    }

    public function create(array $data): Invoice
    {
        return $this->model->create($data);
    }

    public function update(Invoice $invoice, array $data): Invoice
    {
        $invoice->update($data);

        return $invoice->fresh();
    }

    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with('payment')
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    public function getOverdueInvoices(): Collection
    {
        return $this->model
            ->where('status', InvoiceStatus::Unpaid)
            ->where('due_date', '<', now())
            ->with('user')
            ->get();
    }

    public function countByStatus(InvoiceStatus $status): int
    {
        return $this->model->where('status', $status)->count();
    }

    public function monthlyRevenue(int $months = 12): array
    {
        return $this->model
            ->where('status', InvoiceStatus::Paid)
            ->where('created_at', '>=', now()->subMonths($months)->startOfMonth())
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_amount) as revenue")
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->toArray();
    }

    public function existsForPeriod(int $userId, string $billingPeriod): bool
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('billing_period', $billingPeriod)
            ->exists();
    }
}
