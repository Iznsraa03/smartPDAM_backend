<?php

declare(strict_types=1);

use App\Enums\MeterReadingStatus;
use App\Models\User;
use App\Models\WaterMeter;
use App\Models\MeterReading;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

describe('Meter Reading Submission', function () {

    beforeEach(function () {
        Storage::fake('public');
        $this->user = User::factory()->create();
        $this->meter = WaterMeter::factory()->create(['user_id' => $this->user->id]);

        // Seed a minimal tariff group so InvoiceService can calculate
        $group = \App\Models\TariffGroup::create(['name' => 'Test', 'is_active' => true]);
        \App\Models\TariffRate::create(['tariff_group_id' => $group->id, 'start_range' => 0, 'end_range' => 0, 'price_per_m3' => 1500]);
    });

    it('submits a meter reading successfully', function () {
        $photo = UploadedFile::fake()->image('meter.jpg');

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/v1/meter-readings', [
                             'water_meter_id'  => $this->meter->id,
                             'current_reading' => 50.0,
                             'meter_photo'     => $photo,
                         ]);

        $response->assertCreated()
                 ->assertJsonStructure(['message', 'reading' => ['id', 'status', 'current_reading']]);
    });

    it('rejects reading lower than previous', function () {
        MeterReading::factory()->create([
            'water_meter_id'  => $this->meter->id,
            'current_reading' => 100.0,
            'status'          => MeterReadingStatus::Approved,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/v1/meter-readings', [
                             'water_meter_id'  => $this->meter->id,
                             'current_reading' => 50.0,
                         ]);

        $response->assertUnprocessable();
    });

    it('flags abnormal usage as pending_review', function () {
        MeterReading::factory()->create([
            'water_meter_id'  => $this->meter->id,
            'current_reading' => 10.0,
            'status'          => MeterReadingStatus::Approved,
        ]);

        // 1000% increase should trigger pending_review
        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/v1/meter-readings', [
                             'water_meter_id'  => $this->meter->id,
                             'current_reading' => 110.0,
                         ]);

        $response->assertCreated();
        $this->assertDatabaseHas('meter_readings', [
            'water_meter_id' => $this->meter->id,
            'current_reading' => 110.0,
            'status' => MeterReadingStatus::PendingReview->value,
        ]);
    });
});
