<?php

namespace App\Filament\Resources\Trips\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TripForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required(),
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required(),
                Select::make('driver_id')
                    ->relationship('driver', 'name'),
                Select::make('vehicle_id')
                    ->relationship('vehicle', 'id'),
                Select::make('trip_type_id')
                    ->relationship('tripType', 'name'),
                Select::make('travel_route_id')
                    ->relationship('travelRoute', 'name'),
                Select::make('agent_id')
                    ->relationship('agent', 'name'),
                TextInput::make('origin')
                    ->required(),
                TextInput::make('destination')
                    ->required(),
                DateTimePicker::make('start_at')
                    ->required(),
                DateTimePicker::make('completed_at'),
                TextInput::make('status')
                    ->required()
                    ->default('scheduled'),
                TextInput::make('service_kind')
                    ->required()
                    ->default('trip'),
                TextInput::make('customer_segment')
                    ->required()
                    ->default('new'),
                TextInput::make('trip_leg')
                    ->required()
                    ->default('outbound'),
                TextInput::make('passenger_count')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('discount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('final_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('hotel_name')
                    ->tel(),
                Textarea::make('notes')
                    ->columnSpanFull(),
                Textarea::make('cancellation_reason')
                    ->columnSpanFull(),
                TextInput::make('created_by')
                    ->numeric(),
            ]);
    }
}
