<?php

namespace App\Filament\Resources\Comments\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CommentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('content')
                    ->label('Comment')
                    ->markdown()
                    ->columnSpanFull(),

                TextEntry::make('ticket.subject')
                    ->label('Related Ticket')
                    ->badge()
                    ->color('primary'),

                TextEntry::make('user.name')
                    ->label('Author')
                    ->badge()
                    ->color(fn ($record) => $record->is_internal ? 'warning' : 'success'),

                IconEntry::make('is_internal')
                    ->label('Visibility')
                    ->boolean()
                    ->trueIcon('heroicon-o-eye-slash')
                    ->falseIcon('heroicon-o-eye')
                    ->trueColor('warning')
                    ->falseColor('success')
                    ->state(function ($record) {
                        return $record->is_internal ? 'Internal Note' : 'Public Comment';
                    }),

                TextEntry::make('created_at')
                    ->label('Posted')
                    ->dateTime('M j, Y g:i A'),

                TextEntry::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M j, Y g:i A'),
            ]);
    }
}
