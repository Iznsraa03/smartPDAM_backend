<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\MeterReadingDTO;
use App\Enums\MeterReadingStatus;
use App\Events\AbnormalUsageDetected;
use App\Events\MeterReadingSubmitted;
use App\Models\MeterReading;
use App\Models\User;
use App\Repositories\Contracts\MeterReadingRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class MeterReadingService
{
    private const ABNORMAL_USAGE_THRESHOLD = 5.0; // 500% increase

    public function __construct(
        private readonly MeterReadingRepositoryInterface $meterReadingRepository,
    ) {}

    public function submit(User $user, MeterReadingDTO $dto, ?UploadedFile $photo = null): MeterReading
    {
        // Determine previous reading
        $latestReading = $this->meterReadingRepository->latestForMeter($dto->waterMeterId);
        $previousReading = $latestReading ? (float) $latestReading->current_reading : 0.0;

        if ($dto->currentReading <= $previousReading) {
            throw ValidationException::withMessages([
                'current_reading' => ['Current reading must be greater than the previous reading of ' . $previousReading . ' m³.'],
            ]);
        }

        // Upload meter photo if provided
        $photoPath = null;
        if ($photo) {
            $photoPath = Storage::disk('public')->put('meter-readings', $photo);
        }

        $usage  = $dto->currentReading - $previousReading;
        $status = $this->determineStatus($usage, $previousReading);

        $reading = $this->meterReadingRepository->create([
            'water_meter_id'   => $dto->waterMeterId,
            'previous_reading' => $previousReading,
            'current_reading'  => $dto->currentReading,
            'meter_photo'      => $photoPath,
            'reading_date'     => $dto->readingDate,
            'status'           => $status,
        ]);

        event(new MeterReadingSubmitted($reading));

        if ($status === MeterReadingStatus::PendingReview) {
            event(new AbnormalUsageDetected($reading));
        }

        return $reading;
    }

    public function approve(MeterReading $reading): MeterReading
    {
        $updated = $this->meterReadingRepository->update($reading, [
            'status' => MeterReadingStatus::Approved,
        ]);

        // Invoice generation is triggered via event listener
        event(new MeterReadingSubmitted($updated));

        return $updated;
    }

    public function reject(MeterReading $reading, string $reason): MeterReading
    {
        return $this->meterReadingRepository->update($reading, [
            'status'           => MeterReadingStatus::Rejected,
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Determine reading status based on abnormal usage detection.
     * If increase > 500% of previous reading, send to pending_review.
     */
    private function determineStatus(float $usage, float $previousReading): MeterReadingStatus
    {
        if ($previousReading > 0 && ($usage / $previousReading) > self::ABNORMAL_USAGE_THRESHOLD) {
            return MeterReadingStatus::PendingReview;
        }

        return MeterReadingStatus::Approved;
    }
}
