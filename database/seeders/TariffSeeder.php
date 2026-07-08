<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\TariffGroup;
use App\Models\TariffRate;
use Illuminate\Database\Seeder;

class TariffSeeder extends Seeder
{
    public function run(): void
    {
        $group = TariffGroup::firstOrCreate(
            ['name' => 'Tarif Rumah Tangga'],
            [
                'description' => 'Tarif progresif untuk pelanggan rumah tangga',
                'is_active'   => true,
            ]
        );

        // Indonesian typical progressive water tariff blocks (IDR per m³)
        $rates = [
            ['start_range' => 0,  'end_range' => 10, 'price_per_m3' => 2600],
            ['start_range' => 11, 'end_range' => 20, 'price_per_m3' => 4600],
            ['start_range' => 21, 'end_range' => 30, 'price_per_m3' => 7400],
            ['start_range' => 31, 'end_range' => 0,  'price_per_m3' => 10700],  // 0 = unlimited
        ];

        foreach ($rates as $rate) {
            TariffRate::firstOrCreate(
                [
                    'tariff_group_id' => $group->id,
                    'start_range'     => $rate['start_range'],
                    'end_range'       => $rate['end_range'],
                ],
                ['price_per_m3' => $rate['price_per_m3']]
            );
        }
    }
}
