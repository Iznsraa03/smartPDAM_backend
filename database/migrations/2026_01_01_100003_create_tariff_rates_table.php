<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tariff_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tariff_group_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('start_range');  // m3 start (inclusive)
            $table->unsignedInteger('end_range');    // m3 end (inclusive), 0 = unlimited
            $table->decimal('price_per_m3', 12, 2);
            $table->timestamps();

            $table->index(['tariff_group_id', 'start_range', 'end_range']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tariff_rates');
    }
};
