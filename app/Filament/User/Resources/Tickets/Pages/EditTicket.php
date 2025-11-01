<?php

declare(strict_types=1);

namespace App\Filament\User\Resources\Tickets\Pages;

use App\Filament\User\Resources\Tickets\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(fn (): bool => false), // Users cannot delete tickets
            Actions\ViewAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return "Edit Ticket #{$this->record->id}";
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

    protected function beforeSave(): void
    {
        // Only allow editing if ticket is still in "Open" status
        if ($this->record->status->name !== 'Open') {
            $this->notify('danger', 'This ticket cannot be edited because it is no longer in "Open" status.');
            $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
        }
    }
}