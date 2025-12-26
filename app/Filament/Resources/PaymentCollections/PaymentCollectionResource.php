<?php

namespace App\Filament\Resources\PaymentCollections;

use App\Filament\Resources\PaymentCollections\Pages\CreatePaymentCollection;
use App\Filament\Resources\PaymentCollections\Pages\EditPaymentCollection;
use App\Filament\Resources\PaymentCollections\Pages\ListPaymentCollections;
use App\Filament\Resources\PaymentCollections\Schemas\PaymentCollectionForm;
use App\Filament\Resources\PaymentCollections\Tables\PaymentCollectionsTable;
use App\Models\PaymentCollection;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PaymentCollectionResource extends Resource
{
    protected static ?string $model = PaymentCollection::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'id';

    public static function getNavigationGroup(): ?string
    {
        return __('resources.navigation_groups.finance');
    }

    public static function getModelLabel(): string
    {
        return __('resources.payment_collections.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resources.payment_collections.plural_label');
    }

    public static function form(Schema $schema): Schema
    {
        return PaymentCollectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentCollectionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPaymentCollections::route('/'),
            'create' => CreatePaymentCollection::route('/create'),
            'edit' => EditPaymentCollection::route('/{record}/edit'),
        ];
    }
}
