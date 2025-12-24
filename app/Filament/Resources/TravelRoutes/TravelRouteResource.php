<?php

namespace App\Filament\Resources\TravelRoutes;

use App\Filament\Resources\TravelRoutes\Pages\CreateTravelRoute;
use App\Filament\Resources\TravelRoutes\Pages\EditTravelRoute;
use App\Filament\Resources\TravelRoutes\Pages\ListTravelRoutes;
use App\Filament\Resources\TravelRoutes\Schemas\TravelRouteForm;
use App\Filament\Resources\TravelRoutes\Tables\TravelRoutesTable;
use App\Models\TravelRoute;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TravelRouteResource extends Resource
{
    protected static ?string $model = TravelRoute::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    protected static string|\UnitEnum|null $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return TravelRouteForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TravelRoutesTable::configure($table);
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
            'index' => ListTravelRoutes::route('/'),
            'create' => CreateTravelRoute::route('/create'),
            'edit' => EditTravelRoute::route('/{record}/edit'),
        ];
    }
}
