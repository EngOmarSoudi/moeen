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
                    ->label(__('resources.payment_collections.fields.trip'))
                    ->required(),
                Select::make('driver_id')
                    ->relationship('driver', 'name')
                    ->label(__('resources.payment_collections.fields.driver')),
                TextInput::make('amount')
                    ->label(__('resources.payment_collections.fields.amount'))
                    ->required()
                    ->numeric(),
                TextInput::make('received')
                    ->label(__('resources.payment_collections.fields.received'))
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('change')
                    ->label(__('resources.payment_collections.fields.change'))
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('method')
                    ->label(__('resources.payment_collections.fields.method'))
                    ->required()
                    ->default('cash'),
                TextInput::make('status')
                    ->label(__('resources.payment_collections.fields.status'))
                    ->required()
                    ->default('pending'),
                Textarea::make('notes')
                    ->label(__('resources.payment_collections.fields.notes'))
                    ->columnSpanFull(),
                TextInput::make('confirmed_by')
                    ->label(__('resources.payment_collections.fields.confirmed_by'))
                    ->numeric(),
                DateTimePicker::make('confirmed_at')
                    ->label(__('resources.payment_collections.fields.confirmed_at')),
            ]);
    }
}
