<?php

namespace App\Filament\Widgets;

use App\Models\Trip;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class TripSummaryWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();

        $totalTrips = Trip::count();
        $todayTrips = Trip::whereDate('start_at', $today)->count();
        $scheduledTrips = Trip::where('status', 'scheduled')->count();
        $inProgressTrips = Trip::where('status', 'in_progress')->count();
        $completedToday = Trip::where('status', 'completed')
            ->whereDate('completed_at', $today)
            ->count();
        
        $monthlyRevenue = Trip::where('status', 'completed')
            ->whereDate('completed_at', '>=', $thisMonth)
            ->sum('final_amount');

        return [
            Stat::make('Total Trips', $totalTrips)
                ->description('All time trips')
                ->descriptionIcon('heroicon-o-map')
                ->color('primary')
                ->chart([7, 12, 15, 10, 18, 22, 25]),

            Stat::make('Today\'s Trips', $todayTrips)
                ->description('Scheduled for today')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('warning')
                ->chart([3, 5, 7, 6, 8, 9, 10]),

            Stat::make('In Progress', $inProgressTrips)
                ->description('Currently ongoing')
                ->descriptionIcon('heroicon-o-arrow-path')
                ->color('info')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Stat::make('Scheduled', $scheduledTrips)
                ->description('Awaiting start')
                ->descriptionIcon('heroicon-o-clock')
                ->color('secondary'),

            Stat::make('Completed Today', $completedToday)
                ->description('Finished trips today')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Monthly Revenue', 'SAR ' . number_format($monthlyRevenue, 2))
                ->description('This month\'s earnings')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success')
                ->chart([1200, 1800, 2200, 2800, 3200, 3800, 4200]),
        ];
    }
}
