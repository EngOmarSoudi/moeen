<?php

namespace App\Filament\Resources\Trips\Pages;

use App\Filament\Resources\Trips\TripResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTrip extends ViewRecord
{
    protected static string $resource = TripResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('print')
                ->label('Print Trip')
                ->icon('heroicon-o-printer')
                ->color('info')
                ->url(fn ($record) => route('trips.print', ['trip' => $record, 'print' => true]))
                ->openUrlInNewTab(),
            EditAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            \App\Filament\Widgets\TripRouteMapWidget::class,
        ];
    }
}
