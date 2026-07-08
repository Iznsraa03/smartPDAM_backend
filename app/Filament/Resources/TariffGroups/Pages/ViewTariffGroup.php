<?php

namespace App\Filament\Resources\TariffGroups\Pages;

use App\Filament\Resources\TariffGroups\TariffGroupResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTariffGroup extends ViewRecord
{
    protected static string $resource = TariffGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
