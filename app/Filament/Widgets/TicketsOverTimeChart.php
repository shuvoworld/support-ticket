<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TicketsOverTimeChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected static bool $isLazy = false;

    public ?string $filter = '7days';

    public function getHeading(): string
    {
        return 'Tickets Created (Last 7 Days)';
    }

    protected function getData(): array
    {
        $user = auth()->user();

        // Get tickets created in the last 7 days
        $ticketsQuery = Ticket::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date');

        if (!$user->hasPermissionTo('ticket_view_any')) {
            $ticketsQuery->where('user_id', $user->id);
        }

        $tickets = $ticketsQuery->get();

        // Fill in missing dates with 0
        $dates = [];
        $counts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $ticketsForDate = $tickets->where('date', $date)->first();

            $dates[] = now()->subDays($i)->format('M j');
            $counts[] = $ticketsForDate ? $ticketsForDate->count : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Tickets Created',
                    'data' => $counts,
                    'fill' => true,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $dates,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public static function canView(): bool
    {
        return auth()->check();
    }
}