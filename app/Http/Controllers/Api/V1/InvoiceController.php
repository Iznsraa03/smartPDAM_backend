<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Repositories\Contracts\InvoiceRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $invoices = $this->invoiceRepository->paginateForUser($request->user()->id);

        return response()->json(InvoiceResource::collection($invoices)->response()->getData(true));
    }

    public function show(Request $request, Invoice $invoice): JsonResponse
    {
        $this->authorize('view', $invoice);

        return response()->json(new InvoiceResource($invoice->load('payment', 'meterReading')));
    }
}
