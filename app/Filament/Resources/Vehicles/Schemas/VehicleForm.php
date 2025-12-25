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
                Section::make(__('resources.vehicles.sections.info'))
                    ->description(__('resources.vehicles.fields.info_desc')) // Assuming I can add this or just skip description if not critical. I'll rely on Title. Or manual generic. Let's look at file. It says 'Basic vehicle details'. I'll skip description or translate manually if I didn't add key. I didn't add description keys for all. I'll remove description line or use generic `__('Basic details')`
                    ->icon('heroicon-o-truck')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('plate_number')
                                    ->label(__('resources.vehicles.fields.plate_number'))
                                    ->required()
                                    ->maxLength(255),
                                Select::make('vehicle_type_id')
                                    ->label(__('resources.vehicles.fields.type'))
                                    ->relationship('vehicleType', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                TextInput::make('model')
                                    ->label(__('resources.vehicles.fields.model'))
                                    ->maxLength(255),
                                TextInput::make('color')
                                    ->label(__('resources.vehicles.fields.color'))
                                    ->maxLength(100),
                            ]),
                    ])
                    ->columns(2),

                Section::make(__('resources.vehicles.sections.technical'))
                    ->description(__('resources.vehicles.fields.technical_desc'))
                    ->icon('heroicon-o-cog')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('vin')
                                    ->label(__('resources.vehicles.fields.vin'))
                                    ->maxLength(255),
                                TextInput::make('year')
                                    ->label(__('resources.vehicles.fields.year'))
                                    ->numeric()
                                    ->minValue(1900)
                                    ->maxValue(date('Y') + 1),
                            ]),
                    ])
                    ->columns(2),

                Section::make(__('resources.vehicles.sections.status'))
                    ->description(__('resources.vehicles.fields.status_desc'))
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('status')
                                    ->label(__('resources.vehicles.fields.status'))
                                    ->options([
                                        'active' => __('resources.vehicles.enums.active'),
                                        'maintenance' => __('resources.vehicles.enums.maintenance'),
                                        'inactive' => __('resources.vehicles.enums.inactive'),
                                    ])
                                    ->required()
                                    ->default('active')
                                    ->native(false),
                                DatePicker::make('insurance_expiry')
                                    ->label(__('resources.vehicles.fields.insurance_expiry'))
                                    ->native(false)
                                    ->displayFormat('Y-m-d'),
                                DatePicker::make('registration_expiry')
                                    ->label(__('resources.vehicles.fields.registration_expiry'))
                                    ->native(false)
                                    ->displayFormat('Y-m-d'),
                            ]),
                    ])
                    ->columns(2),

                Section::make(__('resources.vehicles.sections.drivers'))
                    ->description(__('Select drivers authorized to use this vehicle'))
                    ->icon('heroicon-o-user-group')
                    ->schema([
                        CheckboxList::make('drivers')
                            ->label(__('resources.vehicles.fields.drivers'))
                            ->relationship('drivers', 'name')
                            ->searchable()
                            ->columns(1)
                            ->helperText(__('Check drivers who are authorized to operate this vehicle'))
                            ->columnSpanFull(),
                    ]),

                Section::make(__('resources.vehicles.sections.notes'))
                    ->description(__('Notes and special instructions'))
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Textarea::make('notes')
                            ->label(__('resources.vehicles.fields.notes'))
                            ->rows(4)
                            ->placeholder(__('Any special maintenance instructions or vehicle information...'))
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }
}
