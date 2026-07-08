<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class InvoiceStatusChart extends ChartWidget
{
    protected ?string $heading = 'Invoice Status Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
