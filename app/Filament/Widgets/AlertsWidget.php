<?php

namespace App\Filament\Widgets;

use App\Models\Alert;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class AlertsWidget extends BaseWidget
{
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Alert::query()
                    ->where('status', 'active')
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\BadgeColumn::make('alert_type.name')
                    ->label('Type')
                    ->colors([
                        'danger' => fn ($record) => $record->alert_type?->severity === 'high',
                        'warning' => fn ($record) => $record->alert_type?->severity === 'medium',
                        'info' => fn ($record) => $record->alert_type?->severity === 'low',
                    ]),
                Tables\Columns\TextColumn::make('title')
                    ->label('Alert Title')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('trip.code')
                    ->label('Trip')
                    ->searchable()
                    ->url(fn ($record) => $record->trip_id 
                        ? route('filament.admin.resources.trips.edit', $record->trip_id) 
                        : null),
                Tables\Columns\TextColumn::make('driver.name')
                    ->label('Driver')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'resolved',
                        'warning' => 'active',
                        'danger' => 'critical',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->heading('Recent Active Alerts')
            ->description('Latest system alerts requiring attention');
    }
}
