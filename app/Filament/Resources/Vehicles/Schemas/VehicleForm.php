<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Vehicle Information')
                    ->description('Basic vehicle details')
                    ->icon('heroicon-o-truck')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('plate_number')
                                    ->label('License Plate')
                                    ->required()
                                    ->maxLength(255),
                                Select::make('vehicle_type_id')
                                    ->label('Vehicle Type')
                                    ->relationship('vehicleType', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                TextInput::make('model')
                                    ->label('Model')
                                    ->maxLength(255),
                                TextInput::make('color')
                                    ->label('Color')
                                    ->maxLength(100),
                            ]),
                    ])
                    ->columns(2),

                Section::make('Technical Details')
                    ->description('VIN, year, and other specifications')
                    ->icon('heroicon-o-cog')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('vin')
                                    ->label('VIN (Vehicle Identification Number)')
                                    ->maxLength(255),
                                TextInput::make('year')
                                    ->label('Year')
                                    ->numeric()
                                    ->minValue(1900)
                                    ->maxValue(date('Y') + 1),
                            ]),
                    ])
                    ->columns(2),

                Section::make('Status & Compliance')
                    ->description('Vehicle status and document expiry dates')
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('status')
                                    ->label('Vehicle Status')
                                    ->options([
                                        'active' => 'Active',
                                        'maintenance' => 'Under Maintenance',
                                        'inactive' => 'Inactive',
                                    ])
                                    ->required()
                                    ->default('active')
                                    ->native(false),
                                DatePicker::make('insurance_expiry')
                                    ->label('Insurance Expiry Date')
                                    ->native(false)
                                    ->displayFormat('Y-m-d'),
                                DatePicker::make('registration_expiry')
                                    ->label('Registration Expiry Date')
                                    ->native(false)
                                    ->displayFormat('Y-m-d'),
                            ]),
                    ])
                    ->columns(2),

                Section::make('Assigned Drivers')
                    ->description('Select drivers authorized to use this vehicle')
                    ->icon('heroicon-o-user-group')
                    ->schema([
                        CheckboxList::make('drivers')
                            ->label('Drivers')
                            ->relationship('drivers', 'name')
                            ->searchable()
                            ->columns(1)
                            ->helperText('Check drivers who are authorized to operate this vehicle')
                            ->columnSpanFull(),
                    ]),

                Section::make('Additional Information')
                    ->description('Notes and special instructions')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(4)
                            ->placeholder('Any special maintenance instructions or vehicle information...')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }
}
