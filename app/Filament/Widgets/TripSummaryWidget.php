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
            Stat::make(__('resources.dashboard.stats.total_trips'), $totalTrips)
                ->description(__('All time trips'))
                ->descriptionIcon('heroicon-o-map')
                ->color('primary')
                ->chart([7, 12, 15, 10, 18, 22, 25]),

            Stat::make(__('resources.dashboard.stats.todays_trips'), $todayTrips)
                ->description(__('Scheduled for today'))
                ->descriptionIcon('heroicon-o-calendar')
                ->color('warning')
                ->chart([3, 5, 7, 6, 8, 9, 10]),

            Stat::make(__('resources.dashboard.stats.in_progress'), $inProgressTrips)
                ->description(__('Currently ongoing'))
                ->descriptionIcon('heroicon-o-arrow-path')
                ->color('info')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Stat::make(__('resources.dashboard.stats.scheduled'), $scheduledTrips)
                ->description(__('Awaiting start'))
                ->descriptionIcon('heroicon-o-clock')
                ->color('secondary'),

            Stat::make(__('resources.dashboard.stats.completed_today'), $completedToday)
                ->description(__('Finished trips today'))
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make(__('resources.dashboard.stats.monthly_revenue'), 'SAR ' . number_format($monthlyRevenue, 2))
                ->description(__('This month\'s earnings'))
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success')
                ->chart([1200, 1800, 2200, 2800, 3200, 3800, 4200]),
        ];
    }
}
