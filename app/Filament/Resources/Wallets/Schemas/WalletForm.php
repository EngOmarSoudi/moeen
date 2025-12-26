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
                    ->label(__('resources.wallets.fields.walletable_type'))
                    ->required(),
                TextInput::make('walletable_id')
                    ->label(__('resources.wallets.fields.walletable_id'))
                    ->required()
                    ->numeric(),
                TextInput::make('balance')
                    ->label(__('resources.wallets.fields.balance'))
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_debt')
                    ->label(__('resources.wallets.fields.total_debt'))
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_collected')
                    ->label(__('resources.wallets.fields.total_collected'))
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('budget_limit')
                    ->label(__('resources.wallets.fields.budget_limit'))
                    ->numeric(),
                TextInput::make('budget_used')
                    ->label(__('resources.wallets.fields.budget_used'))
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
