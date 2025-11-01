<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TicketOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $user = auth()->user();

        if (!$user->hasPermissionTo('ticket_view_any')) {
            // For regular users - show their own stats
            return [
                Stat::make('My Tickets', Ticket::where('user_id', $user->id)->count())
                    ->description('Total tickets created')
                    ->descriptionIcon('heroicon-m-ticket')
                    ->color('primary'),

                Stat::make('Open Tickets', Ticket::where('user_id', $user->id)
                    ->whereDoesntHave('status', function ($query) {
                        $query->where('name', 'Closed');
                    })->count())
                    ->description('Pending resolution')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('warning'),

                Stat::make('Closed Tickets', Ticket::where('user_id', $user->id)
                    ->whereHas('status', function ($query) {
                        $query->where('name', 'Closed');
                    })->count())
                    ->description('Completed')
                    ->descriptionIcon('heroicon-m-check-circle')
                    ->color('success'),
            ];
        }

        // For admins/agents - show system-wide stats
        return [
            Stat::make('Total Tickets', Ticket::count())
                ->description('All tickets in system')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('primary')
                ->chart([7, 12, 10, 14, 15, 18, 20]),

            Stat::make('Open Tickets', Ticket::whereDoesntHave('status', function ($query) {
                $query->where('name', 'Closed');
            })->count())
                ->description('Pending resolution')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Closed Tickets', Ticket::whereHas('status', function ($query) {
                $query->where('name', 'Closed');
            })->count())
                ->description('Completed')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Total Users', User::count())
                ->description('Registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->check();
    }
}