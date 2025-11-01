<?php

namespace App\Filament\Resources\Comments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                RichEditor::make('content')
                    ->label('Comment Content')
                    ->required()
                    ->columnSpanFull()
                    ->placeholder('Enter your comment here...')
                    ->helperText('Add your comment or internal note'),

                Select::make('ticket_id')
                    ->label('Ticket')
                    ->relationship('ticket', 'subject')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Select the ticket this comment belongs to'),

                Select::make('user_id')
                    ->label('Author')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->default(fn () => auth()->id())
                    ->helperText('The person writing this comment'),

                Toggle::make('is_internal')
                    ->label('Internal Note')
                    ->helperText('Internal notes are only visible to support agents, not to customers')
                    ->default(false),
            ]);
    }
}
