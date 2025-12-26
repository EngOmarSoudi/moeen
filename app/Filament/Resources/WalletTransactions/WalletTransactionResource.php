<?php

namespace App\Filament\Resources\WalletTransactions;

use App\Filament\Resources\WalletTransactions\Pages\ManageWalletTransactions;
use App\Models\WalletTransaction;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WalletTransactionResource extends Resource
{
    protected static ?string $model = WalletTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'id';

    public static function getNavigationGroup(): ?string
    {
        return __('resources.navigation_groups.finance');
    }

    public static function getModelLabel(): string
    {
        return __('resources.wallet_transactions.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resources.wallet_transactions.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('wallet_id')
                    ->relationship('wallet', 'id')
                    ->label(__('resources.wallet_transactions.fields.wallet'))
                    ->required(),
                Select::make('trip_id')
                    ->relationship('trip', 'id')
                    ->label(__('resources.wallet_transactions.fields.trip')),
                TextInput::make('type')
                    ->label(__('resources.wallet_transactions.fields.type'))
                    ->required(),
                TextInput::make('amount')
                    ->label(__('resources.wallet_transactions.fields.amount'))
                    ->required()
                    ->numeric(),
                TextInput::make('balance_before')
                    ->label(__('resources.wallet_transactions.fields.balance_before'))
                    ->required()
                    ->numeric(),
                TextInput::make('balance_after')
                    ->label(__('resources.wallet_transactions.fields.balance_after'))
                    ->required()
                    ->numeric(),
                TextInput::make('description')
                    ->label(__('resources.wallet_transactions.fields.description')),
                Textarea::make('metadata')
                    ->label(__('resources.wallet_transactions.fields.metadata'))
                    ->columnSpanFull(),
                TextInput::make('created_by')
                    ->label(__('resources.wallet_transactions.fields.created_by'))
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('wallet.id')
                    ->label(__('resources.wallet_transactions.fields.wallet'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('trip.id')
                    ->label(__('resources.wallet_transactions.fields.trip'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('type')
                    ->label(__('resources.wallet_transactions.fields.type'))
                    ->searchable(),
                TextColumn::make('amount')
                    ->label(__('resources.wallet_transactions.fields.amount'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('balance_before')
                    ->label(__('resources.wallet_transactions.fields.balance_before'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('balance_after')
                    ->label(__('resources.wallet_transactions.fields.balance_after'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('description')
                    ->label(__('resources.wallet_transactions.fields.description'))
                    ->searchable(),
                TextColumn::make('created_by')
                    ->label(__('resources.wallet_transactions.fields.created_by'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageWalletTransactions::route('/'),
        ];
    }
}
