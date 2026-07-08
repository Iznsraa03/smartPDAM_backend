<?php

namespace App\Filament\Resources\TariffGroups\Pages;

use App\Filament\Resources\TariffGroups\TariffGroupResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTariffGroups extends ListRecords
{
    protected static string $resource = TariffGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
