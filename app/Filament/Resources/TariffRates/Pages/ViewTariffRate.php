<?php

namespace App\Filament\Resources\TariffRates\Pages;

use App\Filament\Resources\TariffRates\TariffRateResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTariffRate extends ViewRecord
{
    protected static string $resource = TariffRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
