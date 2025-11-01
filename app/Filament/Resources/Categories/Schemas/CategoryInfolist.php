<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Schema;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Category Name')
                    ->size('text-lg')
                    ->weight('bold'),

                TextEntry::make('slug')
                    ->label('URL Slug')
                    ->copyable()
                    ->copyMessage('Slug copied to clipboard')
                    ->copyMessageDuration(1500),

                TextEntry::make('color')
                    ->label('Display Color')
                    ->badge()
                    ->color(fn (string $state): string => $state)
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

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
