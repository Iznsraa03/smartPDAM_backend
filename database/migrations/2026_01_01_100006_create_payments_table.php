<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->restrictOnDelete();
            $table->string('order_id')->unique();           // Midtrans order_id
            $table->string('transaction_id')->nullable();  // Midtrans transaction_id
            $table->string('payment_method')->nullable();  // gopay, bank_transfer, etc.
            $table->decimal('gross_amount', 14, 2);
            $table->string('snap_token')->nullable();      // Midtrans Snap token
            $table->enum('payment_status', ['pending', 'settlement', 'expire', 'cancel', 'deny'])->default('pending');
            $table->json('midtrans_response')->nullable(); // raw webhook payload
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['invoice_id', 'payment_status']);
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
