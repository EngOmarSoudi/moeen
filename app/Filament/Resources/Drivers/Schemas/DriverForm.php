<?php

namespace App\Filament\Resources\Drivers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class DriverForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Personal Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Full Name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('phone')
                                    ->label('Phone Number')
                                    ->tel()
                                    ->required()
                                    ->maxLength(20),
                                TextInput::make('email')
                                    ->label('Email Address')
                                    ->email()
                                    ->maxLength(255),
                                TextInput::make('id_number')
                                    ->label('National ID Number')
                                    ->maxLength(50),
                            ]),
                        FileUpload::make('photo')
                            ->label('Profile Photo')
                            ->image()
                            ->imageEditor()
                            ->directory('drivers/photos')
                            ->visibility('public')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('License Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('license_number')
                                    ->label('Driver License Number')
                                    ->maxLength(100),
                                DatePicker::make('license_expiry')
                                    ->label('License Expiry Date')
                                    ->native(false)
                                    ->displayFormat('Y-m-d')
                                    ->after('today'),
                            ]),
                    ])
                    ->columns(2),

                Section::make('Account & Status')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('user_id')
                                    ->label('User Account')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')->required(),
                                        TextInput::make('email')->email()->required(),
                                    ]),
                                Select::make('status')
                                    ->label('Driver Status')
                                    ->options([
                                        'available' => 'Available',
                                        'busy' => 'Busy',
                                        'offline' => 'Offline',
                                        'on_break' => 'On Break',
                                    ])
                                    ->required()
                                    ->default('offline')
                                    ->native(false),
                            ]),
                    ])
                    ->columns(2),

                Section::make('Performance Metrics')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('rating')
                                    ->label('Driver Rating')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(5)
                                    ->step(0.1)
                                    ->default(5)
                                    ->suffix('/ 5')
                                    ->disabled()
                                    ->dehydrated(false),
                                TextInput::make('total_trips')
                                    ->label('Total Trips Completed')
                                    ->numeric()
                                    ->default(0)
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),
                    ])
                    ->columns(2),

                Section::make('Additional Notes')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }
}
