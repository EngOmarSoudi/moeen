<?php

namespace App\Filament\Resources\TravelRoutes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
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
                    ->description(__('Precise geographic coordinates for the route'))
                    ->columns(2)
                    ->schema([
                        \App\Filament\Forms\Components\TripLocationPicker::make('origin')
                            ->label(__('resources.trips.fields.origin'))
                            ->columnSpan(1),
                        \App\Filament\Forms\Components\TripLocationPicker::make('destination')
                            ->label(__('resources.trips.fields.destination'))
                            ->columnSpan(1),
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
