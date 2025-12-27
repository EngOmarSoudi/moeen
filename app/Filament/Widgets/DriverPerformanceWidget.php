<?php

namespace App\Filament\Widgets;

use App\Models\Driver;
use App\Models\Trip;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

/**
 * Driver Performance Widget
 * Shows performance metrics for drivers
 * Visible to: Admin, Agent
 */
class DriverPerformanceWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole('admin') || $user->hasRole('agent'));
    }

    protected function getStats(): array
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();

        // Total drivers
        $totalDrivers = Driver::count();
        $activeDrivers = Driver::where('status', 'active')->count();
        $availableDrivers = Driver::where('status', 'available')->count();
        
        // Driver ratings
        $avgRating = Driver::whereNotNull('rating')->avg('rating');
        $topRatedDriver = Driver::whereNotNull('rating')
            ->orderBy('rating', 'desc')
            ->first();
        
        // Driver trips today
        $driversOnTripsToday = Trip::where('status', 'in_progress')
            ->whereDate('start_at', $today)
            ->distinct('driver_id')
            ->count('driver_id');
        
        // Most active driver this month
        $mostActiveDriver = Trip::where('status', 'completed')
            ->whereDate('completed_at', '>=', $thisMonth)
            ->select('driver_id', DB::raw('count(*) as trip_count'))
            ->groupBy('driver_id')
            ->orderBy('trip_count', 'desc')
            ->first();
        
        $mostActiveDriverName = $mostActiveDriver 
            ? Driver::find($mostActiveDriver->driver_id)?->name 
            : 'N/A';

        return [
            Stat::make(__('Total Drivers'), $totalDrivers)
                ->description(__('Registered drivers'))
                ->descriptionIcon('heroicon-o-users')
                ->color('primary')
                ->chart([10, 15, 20, 25, 30, 35, 40]),

            Stat::make(__('Active Drivers'), $activeDrivers)
                ->description(sprintf('%d available', $availableDrivers))
                ->descriptionIcon('heroicon-o-check-badge')
                ->color('success'),

            Stat::make(__('Drivers on Trips'), $driversOnTripsToday)
                ->description(__('Currently driving'))
                ->descriptionIcon('heroicon-o-truck')
                ->color('info'),

            Stat::make(__('Average Rating'), number_format($avgRating ?? 0, 2) . ' ⭐')
                ->description($topRatedDriver ? sprintf('Top: %s (%.2f)', $topRatedDriver->name, $topRatedDriver->rating) : 'No ratings yet')
                ->descriptionIcon('heroicon-o-star')
                ->color('warning'),

            Stat::make(__('Most Active Driver'), $mostActiveDriver ? $mostActiveDriver->trip_count : 0)
                ->description($mostActiveDriverName . ' ' . __('trips this month'))
                ->descriptionIcon('heroicon-o-trophy')
                ->color('success'),
        ];
    }
}
