<?php

namespace App\Filament\Resources\TariffRates\Pages;

use App\Filament\Resources\TariffRates\TariffRateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTariffRates extends ListRecords
{
    protected static string $resource = TariffRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
