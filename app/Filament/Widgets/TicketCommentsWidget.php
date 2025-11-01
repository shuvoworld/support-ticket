<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use App\Models\Comment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\RawJs;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Reactive;

class TicketCommentsWidget extends Widget
{
    protected string $view = 'filament.widgets.ticket-comments-widget';

    protected int | string | array $columnSpan = 'full';

    #[Reactive]
    public ?Ticket $ticket = null;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make('Add Comment')
                ->description('Add a response or internal note to this ticket')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->schema([
                    Forms\Components\Textarea::make('content')
                        ->label('Comment')
                        ->required()
                        ->rows(3)
                        ->placeholder('Type your comment or response here...')
                        ->helperText('This will be visible to the customer unless marked as internal'),

                    Forms\Components\Checkbox::make('is_internal')
                        ->label('Internal Note')
                        ->helperText('Check this if this comment should only be visible to agents and admins')
                        ->default(false),
                ])
                ->columns(1),
        ];
    }

    public function addComment(): void
    {
        $data = $this->validate([
            'content' => ['required', 'string'],
            'is_internal' => ['boolean'],
        ]);

        // Create the comment
        $comment = Comment::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => auth()->id(),
            'content' => $data['content'],
            'is_internal' => $data['is_internal'] ?? false,
        ]);

        // Auto-update status to "In Progress" if agent responds to an open ticket with public comment
        if (auth()->user()->hasRole(['super_admin', 'admin', 'agent']) &&
            $this->ticket->status->name === 'Open' &&
            !$data['is_internal']) {

            $inProgressStatus = \App\Models\Status::where('name', 'In Progress')->first();
            if ($inProgressStatus) {
                $this->ticket->update(['status_id' => $inProgressStatus->id]);

                Notification::make()
                    ->success()
                    ->title('Status Updated')
                    ->body('Ticket status automatically updated to "In Progress"')
                    ->send();
            }
        }

        // Reset the form data
        $this->data = [];

        // Show success notification
        Notification::make()
            ->success()
            ->title($data['is_internal'] ? 'Internal Note Added' : 'Comment Added')
            ->body($data['is_internal']
                ? 'Your internal note has been added and is visible only to agents.'
                : 'Your response has been sent to the customer.')
            ->duration(5000)
            ->send();

        // Refresh the ticket data to show new comment
        $this->ticket->refresh();
    }

    public function getCommentsProperty()
    {
        if (!$this->ticket) {
            return collect();
        }

        return $this->ticket->comments()
            ->with('user')
            ->when(!auth()->user()->hasRole(['super_admin', 'admin', 'agent']), function ($query) {
                $query->where('is_internal', false);
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function render(): View
    {
        return view('filament.widgets.ticket-comments-widget', [
            'comments' => $this->comments,
        ]);
    }
}