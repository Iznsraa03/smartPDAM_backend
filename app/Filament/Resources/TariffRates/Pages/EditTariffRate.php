<?php

namespace App\Filament\Resources\TariffRates\Pages;

use App\Filament\Resources\TariffRates\TariffRateResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTariffRate extends EditRecord
{
    protected static string $resource = TariffRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
