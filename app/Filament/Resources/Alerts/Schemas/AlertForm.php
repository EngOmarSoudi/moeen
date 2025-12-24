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
                    ->relationship('alertType', 'name'),
                Select::make('trip_id')
                    ->relationship('trip', 'id'),
                Select::make('driver_id')
                    ->relationship('driver', 'name'),
                Select::make('vehicle_id')
                    ->relationship('vehicle', 'id'),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('new'),
                DateTimePicker::make('resolved_at'),
                TextInput::make('resolved_by')
                    ->numeric(),
                Textarea::make('resolution_notes')
                    ->columnSpanFull(),
            ]);
    }
}
