<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Filament\Resources\Tickets\TicketResource;
use App\Models\Ticket;
use App\Models\Comment;
use App\Models\Status;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewTicket extends ViewRecord
{
    protected static string $resource = TicketResource::class;

    public function mount($record): void
    {
        parent::mount($record);

        // Auto-assign ticket to current agent if not assigned
        if (auth()->user()->hasRole(['super_admin', 'admin', 'agent']) &&
            !$this->record->agent_id &&
            $this->record->status->name === 'Open') {
            $this->record->update(['agent_id' => auth()->id()]);
            Notification::make()
                ->success()
                ->title('Ticket Assigned')
                ->body("Ticket #{$this->record->id} has been assigned to you.")
                ->send();
        }
    }

    public function getTitle(): string
    {
        return "Ticket #{$this->record->id}: {$this->record->subject}";
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->visible(fn (): bool => auth()->user()->can('update', $this->record)),

            Action::make('assignToMe')
                ->label('Assign to Me')
                ->icon('heroicon-o-user-plus')
                ->color('success')
                ->visible(fn (): bool =>
                    auth()->user()->hasRole(['super_admin', 'admin', 'agent']) &&
                    (!$this->record->agent_id || $this->record->agent_id !== auth()->id())
                )
                ->action(function () {
                    $this->record->update(['agent_id' => auth()->id()]);
                    Notification::make()
                        ->success()
                        ->title('Assigned Successfully')
                        ->body("Ticket #{$this->record->id} has been assigned to you.")
                        ->send();
                }),

            Action::make('markInProgress')
                ->label('Mark In Progress')
                ->icon('heroicon-o-play')
                ->color('warning')
                ->visible(fn (): bool =>
                    auth()->user()->hasRole(['super_admin', 'admin', 'agent']) &&
                    $this->record->status->name === 'Open' &&
                    auth()->user()->can('update', $this->record)
                )
                ->action(function () {
                    $inProgressStatus = Status::where('name', 'In Progress')->first();
                    $this->record->update(['status_id' => $inProgressStatus->id]);
                    Notification::make()
                        ->success()
                        ->title('Status Updated')
                        ->body("Ticket #{$this->record->id} is now marked as In Progress.")
                        ->send();
                }),

            Action::make('closeTicket')
                ->label('Close Ticket')
                ->icon('heroicon-o-check-circle')
                ->color('danger')
                ->visible(fn (): bool =>
                    $this->record->status->name !== 'Closed' &&
                    auth()->user()->can('update', $this->record)
                )
                ->requiresConfirmation()
                ->action(function () {
                    $closedStatus = \App\Models\Status::where('name', 'Closed')->first();
                    $this->record->update([
                        'status_id' => $closedStatus->id,
                        'closed_at' => now(),
                    ]);
                    Notification::make()
                        ->success()
                        ->title('Ticket Closed')
                        ->body("Ticket #{$this->record->id} has been closed.")
                        ->send();
                }),

            Action::make('reopenTicket')
                ->label('Reopen Ticket')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->visible(fn (): bool =>
                    $this->record->status->name === 'Closed' &&
                    auth()->user()->can('update', $this->record)
                )
                ->requiresConfirmation()
                ->modalDescription('Are you sure you want to reopen this ticket?')
                ->action(function () {
                    $openStatus = Status::where('name', 'Open')->first();
                    $this->record->update([
                        'status_id' => $openStatus->id,
                        'closed_at' => null,
                    ]);
                    Notification::make()
                        ->success()
                        ->title('Ticket Reopened')
                        ->body("Ticket #{$this->record->id} has been reopened.")
                        ->send();
                }),

            Action::make('escalateTicket')
                ->label('Escalate')
                ->icon('heroicon-o-arrow-up')
                ->color('info')
                ->visible(fn (): bool =>
                    auth()->user()->hasRole(['super_admin', 'admin']) &&
                    $this->record->status->name !== 'Closed'
                )
                ->requiresConfirmation()
                ->modalDescription('Escalate this ticket to a senior agent for review.')
                ->action(function () {
                    // Update priority to High if not already Urgent
                    if ($this->record->priority !== 'Urgent') {
                        $this->record->update(['priority' => 'High']);
                    }

                    Notification::make()
                        ->info()
                        ->title('Ticket Escalated')
                        ->body("Ticket #{$this->record->id} has been escalated and priority increased.")
                        ->send();
                }),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            \App\Filament\Widgets\TicketCommentsWidget::class,
        ];
    }

    protected function getFooterWidgetsData(): array
    {
        return [
            'ticket' => $this->record,
        ];
    }
}
