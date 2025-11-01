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
}