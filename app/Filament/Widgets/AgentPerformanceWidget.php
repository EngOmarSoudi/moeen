<?php

namespace App\Filament\Widgets;

use App\Models\Agent;
use App\Models\Trip;
use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

/**
 * Agent Performance Widget
 * Shows agent bookings and performance metrics
 * Visible to: Admin only
 */
class AgentPerformanceWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = auth()->user();
        return $user && $user->hasRole('admin');
    }

    protected function getStats(): array
    {
        $thisMonth = now()->startOfMonth();
        $today = now()->startOfDay();

        // Total agents
        $totalAgents = Agent::count();
        $activeAgents = Agent::where('status', 'active')->count();
        
        // Agent bookings this month
        $agentBookingsThisMonth = Trip::whereNotNull('agent_id')
            ->whereDate('start_at', '>=', $thisMonth)
            ->count();
        
        // Direct bookings (no agent) this month
        $directBookingsThisMonth = Trip::whereNull('agent_id')
            ->whereDate('start_at', '>=', $thisMonth)
            ->count();
        
        // Top agent by bookings
        $topAgent = Trip::whereNotNull('agent_id')
            ->whereDate('start_at', '>=', $thisMonth)
            ->select('agent_id', DB::raw('count(*) as booking_count'))
            ->groupBy('agent_id')
            ->orderBy('booking_count', 'desc')
            ->first();
        
        $topAgentName = $topAgent 
            ? Agent::find($topAgent->agent_id)?->name 
            : 'N/A';
        
        // Total agent customers
        $agentCustomers = Customer::whereNotNull('agent_id')->count();
        
        // Revenue through agents this month
        $agentRevenue = Trip::whereNotNull('agent_id')
            ->where('status', 'completed')
            ->whereDate('completed_at', '>=', $thisMonth)
            ->sum('final_amount');

        return [
            Stat::make(__('Total Agents'), $totalAgents)
                ->description(sprintf('%d active', $activeAgents))
                ->descriptionIcon('heroicon-o-building-office')
                ->color('primary'),

            Stat::make(__('Agent Bookings'), $agentBookingsThisMonth)
                ->description(__('This month'))
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('info')
                ->chart([45, 52, 48, 55, 60, 58, 65]),

            Stat::make(__('Direct Bookings'), $directBookingsThisMonth)
                ->description(__('Main company bookings'))
                ->descriptionIcon('heroicon-o-building-storefront')
                ->color('secondary'),

            Stat::make(__('Top Agent'), $topAgent ? $topAgent->booking_count : 0)
                ->description($topAgentName . ' ' . __('bookings'))
                ->descriptionIcon('heroicon-o-trophy')
                ->color('warning'),

            Stat::make(__('Agent Customers'), $agentCustomers)
                ->description(__('Managed by agents'))
                ->descriptionIcon('heroicon-o-user-group')
                ->color('info'),

            Stat::make(__('Agent Revenue'), 'SAR ' . number_format($agentRevenue, 2))
                ->description(__('Through agents this month'))
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success')
                ->chart([8000, 9500, 11000, 12500, 14000, 15500, 17000]),
        ];
    }
}
