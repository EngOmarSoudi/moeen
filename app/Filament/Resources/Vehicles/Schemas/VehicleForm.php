<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('plate_number')
                    ->required(),
                Select::make('vehicle_type_id')
                    ->relationship('vehicleType', 'name')
                    ->required(),
                TextInput::make('model'),
                TextInput::make('color'),
                TextInput::make('vin'),
                TextInput::make('year')
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
                DatePicker::make('insurance_expiry'),
                DatePicker::make('registration_expiry'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
