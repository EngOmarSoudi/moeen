<?php

namespace App\Filament\Resources\Alerts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AlertForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('alert_type_id')
                    ->relationship('alertType', 'name')
                    ->label(__('resources.alerts.fields.alert_type')),
                Select::make('trip_id')
                    ->relationship('trip', 'id')
                    ->label(__('resources.alerts.fields.trip')),
                Select::make('driver_id')
                    ->relationship('driver', 'name')
                    ->label(__('resources.alerts.fields.driver')),
                Select::make('vehicle_id')
                    ->relationship('vehicle', 'id')
                    ->label(__('resources.alerts.fields.vehicle')),
                TextInput::make('title')
                    ->label(__('resources.alerts.fields.title'))
                    ->required(),
                Textarea::make('description')
                    ->label(__('resources.alerts.fields.description'))
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->label(__('resources.alerts.fields.status'))
                    ->required()
                    ->default('new'),
                DateTimePicker::make('resolved_at')
                    ->label(__('resources.alerts.fields.resolved_at')),
                TextInput::make('resolved_by')
                    ->label(__('resources.alerts.fields.resolved_by'))
                    ->numeric(),
                Textarea::make('resolution_notes')
                    ->label(__('resources.alerts.fields.resolution_notes'))
                    ->columnSpanFull(),
            ]);
    }
}
