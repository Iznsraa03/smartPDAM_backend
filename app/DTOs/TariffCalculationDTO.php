<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class TariffCalculationDTO
{
    public function __construct(
        public readonly float $previousReading,
        public readonly float $currentReading,
        public readonly float $usage,
        public readonly float $waterCost,
        public readonly float $administrationFee,
        public readonly float $maintenanceFee,
        public readonly float $penaltyFee,
        public readonly float $totalAmount,
        public readonly array $breakdown,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            previousReading:   $data['previous_reading'],
            currentReading:    $data['current_reading'],
            usage:             $data['usage'],
            waterCost:         $data['water_cost'],
            administrationFee: $data['administration_fee'],
            maintenanceFee:    $data['maintenance_fee'],
            penaltyFee:        $data['penalty_fee'],
            totalAmount:       $data['total_amount'],
            breakdown:         $data['breakdown'],
        );
    }

    public function toArray(): array
    {
        return [
            'previous_reading'   => $this->previousReading,
            'current_reading'    => $this->currentReading,
            'usage'              => $this->usage,
            'water_cost'         => $this->waterCost,
            'administration_fee' => $this->administrationFee,
            'maintenance_fee'    => $this->maintenanceFee,
            'penalty_fee'        => $this->penaltyFee,
            'total_amount'       => $this->totalAmount,
            'breakdown'          => $this->breakdown,
        ];
    }
}
