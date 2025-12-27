<?php

namespace App\Filament\Widgets;

use App\Models\Trip;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

/**
 * Recent Trips Table Widget
 * Shows recent trips with filtering based on user role
 * Visible to: Admin, Agent
 */
class RecentTripsTableWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $heading = 'Recent Trips';

    public static function canView(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole('admin') || $user->hasRole('agent'));
    }

    public function table(Table $table): Table
    {
        $query = Trip::query()->with(['customer', 'driver', 'agent', 'vehicleType']);
        
        // Filter for agents - show only their trips
        $user = auth()->user();
        if ($user->hasRole('agent')) {
            $agent = \App\Models\Agent::where('email', $user->email)->first();
            if ($agent) {
                $query->where('agent_id', $agent->id);
            }
        }

        return $table
            ->query(
                $query->latest()->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label(__('Trip Code'))
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('customer.name')
                    ->label(__('Customer'))
                    ->searchable()
                    ->limit(20),
                
                Tables\Columns\TextColumn::make('driver.name')
                    ->label(__('Driver'))
                    ->searchable()
                    ->limit(20)
                    ->default('—'),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label(__('Status'))
                    ->colors([
                        'secondary' => 'scheduled',
                        'warning' => 'pending',
                        'info' => 'in_progress',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'scheduled',
                        'heroicon-o-arrow-path' => 'in_progress',
                        'heroicon-o-check-circle' => 'completed',
                        'heroicon-o-x-circle' => 'cancelled',
                    ]),
                
                Tables\Columns\TextColumn::make('origin')
                    ->label(__('Origin'))
                    ->limit(25)
                    ->tooltip(fn ($record) => $record->origin),
                
                Tables\Columns\TextColumn::make('destination')
                    ->label(__('Destination'))
                    ->limit(25)
                    ->tooltip(fn ($record) => $record->destination),
                
                Tables\Columns\TextColumn::make('final_amount')
                    ->label(__('Amount'))
                    ->money('SAR')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('start_at')
                    ->label(__('Start Time'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('start_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label(__('View'))
                    ->icon('heroicon-o-eye')
                    ->url(fn (Trip $record): string => route('filament.admin.resources.trips.view', $record))
                    ->openUrlInNewTab(),
            ]);
    }
}
