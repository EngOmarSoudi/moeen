<?php

namespace App\Filament\Resources\Wallets\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WalletForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('walletable_type')
                    ->required(),
                TextInput::make('walletable_id')
                    ->required()
                    ->numeric(),
                TextInput::make('balance')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_debt')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_collected')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('budget_limit')
                    ->numeric(),
                TextInput::make('budget_used')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
