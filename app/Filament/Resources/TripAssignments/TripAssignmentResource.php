<?php

namespace App\Filament\Resources\TripAssignments;

use App\Filament\Resources\TripAssignments\Pages\CreateTripAssignment;
use App\Filament\Resources\TripAssignments\Pages\EditTripAssignment;
use App\Filament\Resources\TripAssignments\Pages\ListTripAssignments;
use App\Filament\Resources\TripAssignments\Schemas\TripAssignmentForm;
use App\Filament\Resources\TripAssignments\Tables\TripAssignmentsTable;
use App\Models\TripAssignment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TripAssignmentResource extends Resource
{
    protected static ?string $model = TripAssignment::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    protected static ?string $recordTitleAttribute = 'id';

    public static function getModelLabel(): string
    {
        return 'Trip Assignment';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Trip Assignments';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Trip Management';
    }

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return TripAssignmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TripAssignmentsTable::configure($table);
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
            'index' => ListTripAssignments::route('/'),
            'create' => CreateTripAssignment::route('/create'),
            'edit' => EditTripAssignment::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
