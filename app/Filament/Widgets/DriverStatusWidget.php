<?php

namespace App\Filament\Widgets;

use App\Models\Driver;
use Filament\Widgets\ChartWidget;

class DriverStatusWidget extends ChartWidget
{
    protected ?string $heading = 'Driver Status Overview';
    protected static ?int $sort = 2;
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $available = Driver::where('status', 'available')->count();
        $busy = Driver::where('status', 'busy')->count();
        $offline = Driver::where('status', 'offline')->count();
        $onBreak = Driver::where('status', 'on_break')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Driver Status',
                    'data' => [$available, $busy, $offline, $onBreak],
                    'backgroundColor' => [
                        'rgb(34, 197, 94)',  // green - available
                        'rgb(251, 191, 36)', // yellow - busy
                        'rgb(239, 68, 68)',  // red - offline
                        'rgb(156, 163, 175)', // gray - on break
                    ],
                ],
            ],
            'labels' => [
                "Available ({$available})", 
                "Busy ({$busy})", 
                "Offline ({$offline})", 
                "On Break ({$onBreak})"
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
