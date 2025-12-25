<?php

namespace App\Filament\Resources\Trips\Pages;

use App\Filament\Resources\Trips\TripResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditTrip extends EditRecord
{
    protected static string $resource = TripResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        $actions = parent::getFormActions();
        
        $printAction = \Filament\Actions\Action::make('print')
            ->label('Print')
            ->icon('heroicon-o-printer')
            ->color('info')
            ->url(fn () => route('trips.print', ['trip' => $this->record, 'print' => true]))
            ->openUrlInNewTab();

        // Insert print action after "Save"
        array_splice($actions, 1, 0, [$printAction]);

        return $actions;
    }
}
