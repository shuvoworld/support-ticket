<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use App\Models\Status;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TicketsByStatusChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected static bool $isLazy = false;

    public ?string $filter = 'all';

    public function getHeading(): string
    {
        return 'Tickets by Status';
    }

    protected function getData(): array
    {
        $user = auth()->user();

        if (!$user->hasPermissionTo('ticket_view_any')) {
            // For regular users - only their own tickets
            $data = Ticket::select('statuses.name', DB::raw('count(*) as count'))
                ->join('statuses', 'tickets.status_id', '=', 'statuses.id')
                ->where('tickets.user_id', $user->id)
                ->groupBy('statuses.name')
                ->orderBy('count', 'desc')
                ->get();
        } else {
            // For admins/agents - all tickets
            $data = Ticket::select('statuses.name', DB::raw('count(*) as count'))
                ->join('statuses', 'tickets.status_id', '=', 'statuses.id')
                ->groupBy('statuses.name')
                ->orderBy('count', 'desc')
                ->get();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Number of Tickets',
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => [
                        'rgba(239, 68, 68, 0.8)',  // red
                        'rgba(245, 158, 11, 0.8)', // amber
                        'rgba(34, 197, 94, 0.8)',  // green
                        'rgba(59, 130, 246, 0.8)', // blue
                        'rgba(168, 85, 247, 0.8)', // purple
                    ],
                    'borderColor' => [
                        'rgba(239, 68, 68, 1)',
                        'rgba(245, 158, 11, 1)',
                        'rgba(34, 197, 94, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(168, 85, 247, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    public static function canView(): bool
    {
        return auth()->check();
    }
}