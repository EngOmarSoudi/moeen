<?php

namespace App\Filament\Resources\TravelRoutes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TravelRouteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('resources.travel_routes.sections.info'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('resources.travel_routes.fields.name_en'))
                            ->placeholder('e.g., Jeddah Airport to Mecca Hotel')
                            ->required(),
                        TextInput::make('name_ar')
                            ->label(__('resources.travel_routes.fields.name_ar'))
                            ->placeholder('مثلاً: من مطار جدة إلى فندق مكة'),
                        TextInput::make('route_type')
                            ->label(__('resources.travel_routes.fields.type'))
                            ->required()
                            ->default('one_way')
                            ->hint('e.g., one_way, round_trip'),
                    ]),

                Section::make(__('resources.travel_routes.sections.locations'))
                    ->description(__('Enter location names or addresses'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('origin')
                            ->label(__('resources.trips.fields.origin'))
                            ->placeholder('e.g., Jeddah Airport')
                            ->required()
                            ->columnSpan(1),
                        TextInput::make('destination')
                            ->label(__('resources.trips.fields.destination'))
                            ->placeholder('e.g., Mecca Hotel')
                            ->required()
                            ->columnSpan(1),
                        // Hidden fields to store coordinates (optional for future use)
                        Hidden::make('origin_lat'),
                        Hidden::make('origin_lng'),
                        Hidden::make('destination_lat'),
                        Hidden::make('destination_lng'),
                    ]),

                Section::make(__('resources.travel_routes.sections.details'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('distance_km')
                            ->label(__('resources.travel_routes.fields.distance'))
                            ->numeric()
                            ->prefix('km'),
                        TextInput::make('duration_minutes')
                            ->label(__('resources.travel_routes.fields.duration'))
                            ->numeric()
                            ->prefix('min'),
                        Textarea::make('description')
                            ->label(__('resources.travel_routes.fields.description'))
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label(__('resources.travel_routes.fields.active'))
                            ->default(true)
                            ->required(),
                    ]),
            ]);
    }
}
