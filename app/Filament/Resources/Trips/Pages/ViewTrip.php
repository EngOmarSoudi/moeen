<?php

namespace App\Filament\Resources\Trips\Pages;

use App\Filament\Resources\Trips\TripResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

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

    public function getTitle(): string | Htmlable
    {
        // Load relationships when viewing
        $this->record->load(['customer', 'agent', 'routeTemplate']);
        return 'Trip ' . ($this->record->code ?? '#');
    }
}
