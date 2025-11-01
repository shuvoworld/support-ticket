<?php

declare(strict_types=1);

namespace App\Filament\User\Resources\Tickets\Pages;

use App\Filament\User\Resources\Tickets\TicketResource;
use App\Models\Comment;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\View\View;

class ViewTicket extends ViewRecord
{
    protected static string $resource = TicketResource::class;

    public function getTitle(): string
    {
        return "Ticket #{$this->record->id}: {$this->record->subject}";
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->visible(fn (): bool => $this->record->status->name === 'Open'),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('content')
                    ->label('Add Message')
                    ->required()
                    ->rows(4)
                    ->placeholder('Type your message here...'),
            ])
            ->statePath('data');
    }

    public function addComment(): void
    {
        $data = $this->form->getState();

        Comment::create([
            'ticket_id' => $this->record->id,
            'user_id' => auth()->id(),
            'content' => $data['content'],
            'is_internal' => false,
        ]);

        $this->form->fill();

        Notification::make()
            ->success()
            ->title('Message Sent')
            ->body('Your message has been sent successfully.')
            ->send();
    }

    protected function getViewData(): array
    {
        return [
            'comments' => $this->record->comments()
                ->with('user')
                ->where('is_internal', false)
                ->orderBy('created_at', 'asc')
                ->get(),
        ];
    }

    public function render(): View
    {
        return view('filament.user.resources.tickets.pages.view-ticket', $this->getViewData())
            ->layout('filament-panels::pages.layout');
    }
}