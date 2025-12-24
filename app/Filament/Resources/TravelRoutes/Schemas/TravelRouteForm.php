<?php

namespace App\Filament\Resources\TravelRoutes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TravelRouteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('name_ar'),
                TextInput::make('origin')
                    ->required(),
                TextInput::make('destination')
                    ->required(),
                TextInput::make('distance_km')
                    ->numeric(),
                TextInput::make('duration_minutes')
                    ->numeric(),
                TextInput::make('route_type')
                    ->required()
                    ->default('one_way'),
                Textarea::make('description')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
