<?php

namespace App\Filament\Resources\SavedPlaces\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class SavedPlaceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Place Information')
                    ->description('Basic details about this location')
                    ->icon('heroicon-o-map-pin')
                    ->columnSpan(2)
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Place Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g., King Fahd International Airport'),

                                TextInput::make('name_ar')
                                    ->label('Arabic Name')
                                    ->maxLength(255)
                                    ->placeholder('الاسم بالعربية'),

                                Select::make('place_type')
                                    ->label('Place Type')
                                    ->options([
                                        'airport' => 'Airport',
                                        'hotel' => 'Hotel',
                                        'bus_station' => 'Bus Station',
                                        'train_station' => 'Train Station',
                                        'landmark' => 'Landmark',
                                        'office' => 'Office',
                                        'residential' => 'Residential',
                                        'other' => 'Other',
                                    ])
                                    ->required()
                                    ->default('other')
                                    ->native(false),

                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true)
                                    ->helperText('Inactive places won\'t appear in selection lists'),
                            ]),

                        Textarea::make('address')
                            ->label('Full Address')
                            ->rows(2)
                            ->placeholder('Enter the full address of this location')
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(2)
                            ->placeholder('Additional notes or directions')
                            ->columnSpanFull(),
                    ]),

                Section::make('Location Coordinates')
                    ->description('GPS coordinates for map display')
                    ->icon('heroicon-o-globe-alt')
                    ->columnSpan(1)
                    ->schema([
                        TextInput::make('latitude')
                            ->label('Latitude')
                            ->required()
                            ->numeric()
                            ->step(0.00000001)
                            ->placeholder('e.g., 24.7136')
                            ->helperText('Range: -90 to 90'),

                        TextInput::make('longitude')
                            ->label('Longitude')
                            ->required()
                            ->numeric()
                            ->step(0.00000001)
                            ->placeholder('e.g., 46.6753')
                            ->helperText('Range: -180 to 180'),
                    ]),
            ]);
    }
}
