<?php

namespace App\Filament\Resources\Drivers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class DriverForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('resources.drivers.sections.personal'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('resources.drivers.fields.name'))
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('phone')
                                    ->label(__('resources.drivers.fields.phone'))
                                    ->tel()
                                    ->required()
                                    ->maxLength(20),
                                TextInput::make('email')
                                    ->label(__('resources.drivers.fields.email'))
                                    ->email()
                                    ->maxLength(255),
                                TextInput::make('id_number')
                                    ->label(__('resources.drivers.fields.id_number'))
                                    ->maxLength(50),
                            ]),
                        FileUpload::make('photo')
                            ->label(__('resources.drivers.fields.photo'))
                            ->image()
                            ->imageEditor()
                            ->directory('drivers/photos')
                            ->visibility('public')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make(__('resources.drivers.sections.license'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('license_number')
                                    ->label(__('resources.drivers.fields.license_number'))
                                    ->maxLength(100),
                                DatePicker::make('license_expiry')
                                    ->label(__('resources.drivers.fields.license_expiry'))
                                    ->native(false)
                                    ->displayFormat('Y-m-d')
                                    ->after('today'),
                            ]),
                    ])
                    ->columns(2),

                Section::make(__('resources.drivers.sections.account'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('user_id')
                                    ->label(__('resources.drivers.fields.user'))
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')->required()->label(__('resources.customers.fields.name')), // Re-using customer name label for generic name
                                        TextInput::make('email')->email()->required()->label(__('resources.customers.fields.email')),
                                    ]),
                                Select::make('status')
                                    ->label(__('resources.drivers.fields.status'))
                                    ->options([
                                        'available' => __('resources.drivers.enums.available'),
                                        'busy' => __('resources.drivers.enums.busy'),
                                        'offline' => __('resources.drivers.enums.offline'),
                                        'on_break' => __('resources.drivers.enums.on_break'),
                                    ])
                                    ->required()
                                    ->default('offline')
                                    ->native(false),
                            ]),
                    ])
                    ->columns(2),

                Section::make(__('resources.drivers.sections.performance'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('rating')
                                    ->label(__('resources.drivers.fields.rating'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(5)
                                    ->step(0.1)
                                    ->default(5)
                                    ->suffix('/ 5')
                                    ->disabled()
                                    ->dehydrated(false),
                                TextInput::make('total_trips')
                                    ->label(__('resources.drivers.fields.total_trips'))
                                    ->numeric()
                                    ->default(0)
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),
                    ])
                    ->columns(2),

                Section::make(__('resources.drivers.sections.vehicles'))
                    ->description(__('Select vehicles assigned to this driver'))
                    ->icon('heroicon-o-truck')
                    ->schema([
                        CheckboxList::make('vehicles')
                            ->label(__('resources.drivers.fields.vehicles'))
                            ->relationship('vehicles', 'plate_number')
                            ->searchable()
                            ->columns(1)
                            ->helperText(__('Check vehicles that this driver is authorized to use'))
                            ->columnSpanFull(),
                    ]),

                Section::make(__('resources.drivers.sections.notes'))
                    ->schema([
                        Textarea::make('notes')
                            ->label(__('resources.drivers.fields.notes'))
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }
}
