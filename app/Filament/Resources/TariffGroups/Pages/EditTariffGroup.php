<?php

namespace App\Filament\Resources\TariffGroups\Pages;

use App\Filament\Resources\TariffGroups\TariffGroupResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTariffGroup extends EditRecord
{
    protected static string $resource = TariffGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
