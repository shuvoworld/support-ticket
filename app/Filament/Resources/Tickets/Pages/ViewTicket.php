<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Filament\Resources\Tickets\TicketResource;
use App\Models\Ticket;
use App\Models\Comment;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

class ViewTicket extends ViewRecord
{
    protected static string $resource = TicketResource::class;

    public ?Model $record = null;

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
                ->action(function () {
                    $openStatus = \App\Models\Status::where('name', 'Open')->first();
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
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('content')
                    ->label('Add Comment')
                    ->required()
                    ->rows(4)
                    ->placeholder('Type your comment here...'),

                Forms\Components\Checkbox::make('is_internal')
                    ->label('Internal Note (not visible to customer)')
                    ->visible(fn (): bool => auth()->user()->hasRole(['super_admin', 'admin', 'agent'])),
            ])
            ->statePath('data');
    }

    public function addComment(): void
    {
        $data = $this->form->getState();

        $comment = Comment::create([
            'ticket_id' => $this->record->id,
            'user_id' => auth()->id(),
            'content' => $data['content'],
            'is_internal' => $data['is_internal'] ?? false,
        ]);

        // Auto-update status to "In Progress" if agent responds to an open ticket
        if (auth()->user()->hasRole(['super_admin', 'admin', 'agent']) &&
            $this->record->status->name === 'Open' &&
            !$data['is_internal']) {

            $inProgressStatus = \App\Models\Status::where('name', 'In Progress')->first();
            $this->record->update(['status_id' => $inProgressStatus->id]);
        }

        $this->form->fill();

        Notification::make()
            ->success()
            ->title('Comment Added')
            ->body('Your comment has been added successfully.')
            ->send();
    }

    public function getFooterWidgets(): array
    {
        return [
            \App\Filament\Widgets\TicketCommentsWidget::class,
        ];
    }

    protected function getViewData(): array
    {
        return [
            'comments' => $this->record->comments()
                ->with('user')
                ->when(!auth()->user()->hasRole(['super_admin', 'admin', 'agent']), function ($query) {
                    $query->where('is_internal', false);
                })
                ->orderBy('created_at', 'asc')
                ->get(),
        ];
    }

    public function render(): View
    {
        return view('filament.resources.tickets.pages.view-ticket', $this->getViewData())
            ->layout('filament-panels::pages.layout');
    }
}
