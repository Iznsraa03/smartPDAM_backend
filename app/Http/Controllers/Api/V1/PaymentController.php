<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Invoice;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService,
        private readonly PaymentRepositoryInterface $paymentRepository,
    ) {}

    /**
     * Create a Midtrans Snap transaction for an invoice.
     */
    public function create(Request $request, Invoice $invoice): JsonResponse
    {
        $this->authorize('pay', $invoice);

        $payment = $this->paymentService->createSnapTransaction($invoice);

        return response()->json([
            'message' => 'Payment transaction created.',
            'payment' => new PaymentResource($payment),
        ], 201);
    }

    /**
     * Handle Midtrans payment webhook (no auth guard).
     */
    public function webhook(Request $request): JsonResponse
    {
        $this->paymentService->handleWebhook($request->all());

        return response()->json(['message' => 'OK']);
    }

    /**
     * List user payment history.
     */
    public function index(Request $request): JsonResponse
    {
        $payments = $this->paymentRepository->paginateForUser($request->user()->id);

        return response()->json(PaymentResource::collection($payments)->response()->getData(true));
    }
}
