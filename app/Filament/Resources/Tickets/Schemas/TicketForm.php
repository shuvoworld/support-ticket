<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('subject')
                    ->label('Subject')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter the ticket subject'),

                RichEditor::make('content')
                    ->label('Description')
                    ->required()
                    ->columnSpanFull()
                    ->placeholder('Describe the issue in detail'),

                Select::make('priority')
                    ->label('Priority')
                    ->options([
                        'Low' => 'Low',
                        'Medium' => 'Medium',
                        'High' => 'High',
                        'Urgent' => 'Urgent'
                    ])
                    ->default('Medium')
                    ->required()
                    ->helperText('Select the urgency level of this ticket'),

                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Select the category that best describes this issue'),

                Select::make('user_id')
                    ->label('Requester')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Select the user who created this ticket'),

                Select::make('agent_id')
                    ->label('Assigned Agent')
                    ->relationship('agent', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->placeholder('Not assigned yet')
                    ->helperText('Assign this ticket to a support agent'),

                Select::make('status_id')
                    ->label('Status')
                    ->relationship('status', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Current status of this ticket'),

                DateTimePicker::make('closed_at')
                    ->label('Closed At')
                    ->nullable()
                    ->helperText('When was this ticket closed? Leave blank if still open'),

                Toggle::make('mark_closed')
                    ->label('Mark as Closed')
                    ->helperText('Toggle to indicate this ticket is resolved'),
            ]);
    }
}
