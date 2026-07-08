<?php

declare(strict_types=1);

use App\DTOs\TariffCalculationDTO;
use App\Models\TariffGroup;
use App\Models\TariffRate;
use App\Repositories\Contracts\TariffRepositoryInterface;
use App\Services\WaterTariffCalculationService;

describe('WaterTariffCalculationService', function () {

    beforeEach(function () {
        // Build a mock tariff group with progressive rates
        $this->tariffGroup = new TariffGroup([
            'id'          => 1,
            'name'        => 'Residential',
            'description' => 'Test tariff',
            'is_active'   => true,
        ]);

        $rates = collect([
            new TariffRate(['start_range' => 0,  'end_range' => 10, 'price_per_m3' => 1500]),
            new TariffRate(['start_range' => 11, 'end_range' => 20, 'price_per_m3' => 2500]),
            new TariffRate(['start_range' => 21, 'end_range' => 0,  'price_per_m3' => 4000]),
        ]);
        $this->tariffGroup->setRelation('tariffRates', $rates);

        $repository = Mockery::mock(TariffRepositoryInterface::class);
        $repository->shouldReceive('findActiveGroup')->andReturn($this->tariffGroup);

        $this->service = new WaterTariffCalculationService($repository);
    });

    it('calculates usage correctly', function () {
        $result = $this->service->calculate(100.0, 115.0);

        expect($result->usage)->toBe(15.0);
    });

    it('returns a TariffCalculationDTO', function () {
        $result = $this->service->calculate(0.0, 10.0);

        expect($result)->toBeInstanceOf(TariffCalculationDTO::class);
    });

    it('calculates water cost for first block only (10 m³)', function () {
        $result = $this->service->calculate(0.0, 10.0);

        // 10 m³ × 1500 = 15000
        expect($result->waterCost)->toBe(15000.0);
    });

    it('calculates progressive cost across multiple blocks', function () {
        // 20 m³: first block [0-10] = 10 m³ × 1500 = 15000
        //        second block [11-20] = 9 m³ × 2500 = 22500
        //        total = 37500
        $result = $this->service->calculate(0.0, 20.0);

        expect($result->waterCost)->toBe(37500.0);
    });

    it('includes administration fee in total', function () {
        $result = $this->service->calculate(0.0, 10.0);

        expect($result->administrationFee)->toBe(5000.0);
        expect($result->totalAmount)->toBe($result->waterCost + 5000.0);
    });

    it('applies late penalty when isLate is true', function () {
        $result = $this->service->calculate(0.0, 10.0, isLate: true);

        expect($result->penaltyFee)->toBeGreaterThan(0);
        expect($result->totalAmount)->toBe(
            $result->waterCost + $result->administrationFee + $result->penaltyFee
        );
    });

    it('has zero penalty when not late', function () {
        $result = $this->service->calculate(0.0, 10.0, isLate: false);

        expect($result->penaltyFee)->toBe(0.0);
    });

    it('throws InvalidArgumentException when current <= previous reading', function () {
        expect(fn () => $this->service->calculate(50.0, 30.0))
            ->toThrow(InvalidArgumentException::class);
    });

    it('provides a breakdown array', function () {
        $result = $this->service->calculate(0.0, 20.0);

        expect($result->breakdown)->toBeArray()->not->toBeEmpty();
    });
});
