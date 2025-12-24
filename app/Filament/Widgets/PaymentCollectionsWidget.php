<?php

namespace App\Filament\Widgets;

use App\Models\PaymentCollection;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PaymentCollectionsWidget extends ChartWidget
{
    protected ?string $heading = 'Payment Collections - Last 7 Days';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M d');
            
            $amount = PaymentCollection::whereDate('payment_date', $date)
                ->where('status', 'confirmed')
                ->sum('amount');
            
            $data[] = (float) $amount;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Collections (SAR)',
                    'data' => $data,
                    'borderColor' => 'rgb(184, 134, 11)',
                    'backgroundColor' => 'rgba(184, 134, 11, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
