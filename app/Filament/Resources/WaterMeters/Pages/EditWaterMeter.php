<?php

namespace App\Filament\Resources\WaterMeters\Pages;

use App\Filament\Resources\WaterMeters\WaterMeterResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWaterMeter extends EditRecord
{
    protected static string $resource = WaterMeterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
