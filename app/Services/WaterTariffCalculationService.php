<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\TariffCalculationDTO;
use App\Models\TariffGroup;
use App\Repositories\Contracts\TariffRepositoryInterface;
use InvalidArgumentException;

/**
 * Water Tariff Calculation Engine.
 *
 * Implements progressive (block) tariff billing:
 *   - Splits usage across defined tariff blocks
 *   - Adds fixed administration fee
 *   - Adds late penalty if past due date
 */
class WaterTariffCalculationService
{
    private const ADMINISTRATION_FEE = 10000.0;
    private const MAINTENANCE_FEE    = 5000.0;
    private const PENALTY_PERCENT    = 0.02; // 2% per month

    public function __construct(
        private readonly TariffRepositoryInterface $tariffRepository,
    ) {}

    /**
     * Calculate a full billing breakdown.
     *
     * @throws InvalidArgumentException
     */
    public function calculate(
        float $previousReading,
        float $currentReading,
        bool  $isLate = false,
    ): TariffCalculationDTO {
        if ($currentReading <= $previousReading) {
            throw new InvalidArgumentException(
                'Current reading must be greater than previous reading.'
            );
        }

        $usage = round($currentReading - $previousReading, 2);

        $tariffGroup = $this->tariffRepository->findActiveGroup();

        if ($tariffGroup === null) {
            throw new InvalidArgumentException('No active tariff group configured.');
        }

        $waterCost   = $this->calculateProgressiveCost($usage, $tariffGroup);
        $adminFee    = self::ADMINISTRATION_FEE;
        $maintFee    = self::MAINTENANCE_FEE;
        $penalty     = $isLate ? round($waterCost * self::PENALTY_PERCENT, 2) : 0.0;
        $total       = round($waterCost + $adminFee + $maintFee + $penalty, 2);

        return new TariffCalculationDTO(
            previousReading:   $previousReading,
            currentReading:    $currentReading,
            usage:             $usage,
            waterCost:         $waterCost,
            administrationFee: $adminFee,
            maintenanceFee:    $maintFee,
            penaltyFee:        $penalty,
            totalAmount:       $total,
            breakdown:         $this->buildBreakdown($usage, $tariffGroup),
        );
    }

    /**
     * Calculate the water cost using progressive block tariff.
     *
     * For each tariff rate block [start..end], determine how many m³
     * of the total usage fall within that block and multiply by price_per_m3.
     */
    private function calculateProgressiveCost(float $usage, TariffGroup $tariffGroup): float
    {
        $totalCost = 0.0;

        foreach ($tariffGroup->tariffRates as $rate) {
            $blockStart = (float) $rate->start_range;
            $blockEnd   = $rate->end_range === 0 ? PHP_FLOAT_MAX : (float) $rate->end_range;

            // Usage that falls within this block
            $usageInBlock = max(0.0, min($usage, $blockEnd) - $blockStart);

            if ($usageInBlock <= 0) {
                break;
            }

            $totalCost += $usageInBlock * (float) $rate->price_per_m3;
        }

        return round($totalCost, 2);
    }

    /**
     * Build a human-readable cost breakdown by tariff block.
     */
    private function buildBreakdown(float $usage, TariffGroup $tariffGroup): array
    {
        $breakdown      = [];
        $remainingUsage = $usage;

        foreach ($tariffGroup->tariffRates as $rate) {
            if ($remainingUsage <= 0) {
                break;
            }

            $blockStart = (float) $rate->start_range;
            $blockEnd   = $rate->end_range === 0 ? PHP_FLOAT_MAX : (float) $rate->end_range;
            $blockSize  = $blockEnd - $blockStart + 1;

            if ($usage > $blockStart) {
                $usageInBlock = min(min($remainingUsage, $blockSize), $usage - $blockStart);

                $breakdown[] = [
                    'block'          => "{$rate->start_range} - " . ($rate->end_range === 0 ? '∞' : $rate->end_range) . " m³",
                    'usage_in_block' => round($usageInBlock, 2),
                    'price_per_m3'   => (float) $rate->price_per_m3,
                    'subtotal'       => round($usageInBlock * (float) $rate->price_per_m3, 2),
                ];

                $remainingUsage -= $usageInBlock;
            }
        }

        return $breakdown;
    }
}
