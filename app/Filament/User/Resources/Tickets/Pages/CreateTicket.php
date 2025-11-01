<?php

declare(strict_types=1);

namespace App\Filament\User\Resources\Tickets\Pages;

use App\Filament\User\Resources\Tickets\TicketResource;
use App\Models\Status;
use Filament\Resources\Pages\CreateRecord;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set default status to "Open"
        $openStatus = Status::where('name', 'Open')->first();
        if ($openStatus) {
            $data['status_id'] = $openStatus->id;
        }

        // Set the user ID
        $data['user_id'] = auth()->id();

        return $data;
    }

    public function getTitle(): string
    {
        return 'Create New Ticket';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}