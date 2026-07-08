<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('meter_reading_id')->nullable()->constrained()->nullOnDelete();
            $table->string('invoice_number')->unique();
            $table->decimal('previous_reading', 12, 2)->default(0);
            $table->decimal('current_reading', 12, 2);
            $table->decimal('usage', 12, 2);
            $table->decimal('water_cost', 14, 2)->default(0);
            $table->decimal('administration_fee', 14, 2)->default(0);
            $table->decimal('penalty_fee', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2);
            $table->date('due_date');
            $table->string('billing_period', 7);  // e.g. "2024-01"
            $table->enum('status', ['unpaid', 'paid', 'overdue'])->default('unpaid');
            $table->timestamps();

            $table->index(['user_id', 'billing_period']);
            $table->index(['status', 'due_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
