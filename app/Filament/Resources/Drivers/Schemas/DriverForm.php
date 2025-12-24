<?php

namespace App\Filament\Resources\Drivers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DriverForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('license_number'),
                DatePicker::make('license_expiry'),
                TextInput::make('id_number'),
                TextInput::make('status')
                    ->required()
                    ->default('offline'),
                Select::make('user_id')
                    ->relationship('user', 'name'),
                TextInput::make('photo'),
                Textarea::make('notes')
                    ->columnSpanFull(),
                TextInput::make('rating')
                    ->required()
                    ->numeric()
                    ->default(5),
                TextInput::make('total_trips')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
