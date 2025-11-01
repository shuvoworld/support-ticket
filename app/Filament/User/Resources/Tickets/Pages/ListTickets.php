<?php

declare(strict_types=1);

namespace App\Filament\User\Resources\Tickets\Pages;

use App\Filament\User\Resources\Tickets\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create Ticket')
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTitle(): string
    {
        return 'My Tickets';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // Add user ticket overview widget here if needed
        ];
    }
}