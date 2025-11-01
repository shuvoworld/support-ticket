<?php

namespace App\Filament\Resources\Statuses\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Schema;

class StatusInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Status Name')
                    ->size('text-lg')
                    ->weight('bold')
                    ->badge()
                    ->color('primary'),

                TextEntry::make('tickets_count')
                    ->label('Total Tickets')
                    ->counts('tickets'),

                IconEntry::make('has_tickets')
                    ->label('Active Usage')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->tickets()->exists())
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),

                TextEntry::make('tickets.last.created_at')
                    ->label('Last Ticket')
                    ->dateTime('M j, Y g:i A')
                    ->getStateUsing(fn ($record) => $record->tickets()->latest()->first()?->created_at)
                    ->placeholder('No tickets yet'),

                TextEntry::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y g:i A'),

                TextEntry::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M j, Y g:i A'),
            ]);
    }
}
