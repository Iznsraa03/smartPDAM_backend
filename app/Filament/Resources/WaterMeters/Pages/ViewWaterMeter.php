<?php

namespace App\Filament\Resources\WaterMeters\Pages;

use App\Filament\Resources\WaterMeters\WaterMeterResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWaterMeter extends ViewRecord
{
    protected static string $resource = WaterMeterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
