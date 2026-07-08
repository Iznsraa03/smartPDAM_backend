<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meter_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('water_meter_id')->constrained()->cascadeOnDelete();
            $table->decimal('previous_reading', 12, 2)->default(0);
            $table->decimal('current_reading', 12, 2);
            $table->decimal('usage', 12, 2)->storedAs('current_reading - previous_reading');
            $table->string('meter_photo')->nullable();
            $table->date('reading_date');
            $table->enum('status', ['pending', 'approved', 'rejected', 'pending_review'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->index(['water_meter_id', 'reading_date']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meter_readings');
    }
};
