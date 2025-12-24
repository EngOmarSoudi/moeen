<?php

namespace App\Filament\Resources\PaymentCollections\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PaymentCollectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('trip_id')
                    ->relationship('trip', 'id')
                    ->required(),
                Select::make('driver_id')
                    ->relationship('driver', 'name'),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('received')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('change')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('method')
                    ->required()
                    ->default('cash'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                Textarea::make('notes')
                    ->columnSpanFull(),
                TextInput::make('confirmed_by')
                    ->numeric(),
                DateTimePicker::make('confirmed_at'),
            ]);
    }
}
