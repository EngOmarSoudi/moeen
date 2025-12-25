<?php

namespace App\Filament\Resources\Trips\Pages;

use App\Filament\Resources\Trips\TripResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTrip extends CreateRecord
{
    protected static string $resource = TripResource::class;

    protected function getFormActions(): array
    {
        $actions = parent::getFormActions();
        
        $printAction = \Filament\Actions\Action::make('print')
            ->label('Create & Print')
            ->icon('heroicon-o-printer')
            ->color('info')
            ->action(function () {
                $this->create();
            })
            ->after(function () {
                $this->redirect(route('trips.print', ['trip' => $this->record, 'print' => true]));
            });

        // Insert print action before the second action (usually Create Another)
        if (count($actions) >= 2) {
            array_splice($actions, 2, 0, [$printAction]);
        } else {
            $actions[] = $printAction;
        }

        return $actions;
    }
}
