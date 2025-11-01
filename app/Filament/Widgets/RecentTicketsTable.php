<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentTicketsTable extends BaseWidget
{
    protected static ?int $sort = 4;

    protected static bool $isLazy = false;

    public function table(Table $table): Table
    {
        $user = auth()->user();

        $query = Ticket::with(['user', 'status', 'category'])
            ->latest()
            ->limit(10);

        if (!$user->hasPermissionTo('ticket_view_any')) {
            $query->where('user_id', $user->id);
        }

        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('subject')
                    ->label('Subject')
                    ->searchable()
                    ->limit(50)
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Requester')
                    ->sortable()
                    ->toggleable(auth()->user()->hasPermissionTo('ticket_view_any')),

                Tables\Columns\TextColumn::make('status.name')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Open' => 'danger',
                        'In Progress' => 'warning',
                        'Pending' => 'info',
                        'Closed' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('priority')
                    ->label('Priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'high' => 'danger',
                        'medium' => 'warning',
                        'low' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(),
            ])
            ->actions([
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Ticket $record): string => route('filament.admin.resources.tickets.view', $record))
                    ->openUrlInNewTab(false),
            ])
            ->paginated([5, 10, 25])
            ->searchable()
            ->striped();
    }

    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->hasAnyPermission(['ticket_view_any', 'ticket_view_own']);
    }
}