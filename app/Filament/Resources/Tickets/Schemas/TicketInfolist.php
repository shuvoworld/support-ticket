<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Grid;
use Filament\Schemas\Schema;

class TicketInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Ticket Information Section
                Section::make('Ticket Information')
                    ->description('Basic ticket details and status')
                    ->icon('heroicon-o-ticket')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('id')
                            ->label('Ticket ID')
                            ->badge()
                            ->color('primary')
                            ->formatStateUsing(fn ($state) => "#{$state}"),

                        TextEntry::make('status.name')
                            ->label('Status')
                            ->badge()
                            ->color(fn ($record) => match ($record->status->name) {
                                'Open' => 'danger',
                                'In Progress' => 'warning',
                                'Closed' => 'success',
                                default => 'gray',
                            })
                            ->icon(fn ($record) => match ($record->status->name) {
                                'Open' => 'heroicon-o-exclamation-circle',
                                'In Progress' => 'heroicon-o-clock',
                                'Closed' => 'heroicon-o-check-circle',
                                default => 'heroicon-o-question-mark-circle',
                            }),

                        TextEntry::make('priority')
                            ->label('Priority')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Low' => 'success',
                                'Medium' => 'warning',
                                'High' => 'danger',
                                'Urgent' => 'danger',
                                default => 'gray',
                            })
                            ->icon(fn (string $state) => match ($state) {
                                'Urgent' => 'heroicon-o-exclamation-triangle',
                                'High' => 'heroicon-o-arrow-up-circle',
                                'Medium' => 'heroicon-o-minus-circle',
                                'Low' => 'heroicon-o-arrow-down-circle',
                                default => 'heroicon-o-dash',
                            }),
                    ]),

                // Main Content Section
                Section::make('Ticket Details')
                    ->description('Subject and description of the ticket')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        TextEntry::make('subject')
                            ->label('Subject')
                            ->size('text-lg')
                            ->weight('bold')
                            ->columnSpanFull(),

                        TextEntry::make('content')
                            ->label('Description')
                            ->markdown()
                            ->columnSpanFull(),
                    ]),

                // Assignment and Classification Section
                Section::make('Assignment & Classification')
                    ->description('Ticket categorization and assignment details')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('category.name')
                            ->label('Category')
                            ->badge()
                            ->color(fn ($record) => $record->category->color ?? 'gray')
                            ->icon('heroicon-o-tag'),

                        TextEntry::make('department.name')
                            ->label('Department')
                            ->badge()
                            ->color('gray')
                            ->icon('heroicon-o-building-office')
                            ->placeholder('Unassigned'),

                        TextEntry::make('agent.name')
                            ->label('Assigned Agent')
                            ->badge()
                            ->color('info')
                            ->icon('heroicon-o-user')
                            ->placeholder('Unassigned')
                            ->formatStateUsing(function ($state, $record) {
                                return $state ?? 'Unassigned';
                            }),
                    ]),

                // People Section
                Section::make('People')
                    ->description('Information about the requester and agent')
                    ->icon('heroicon-o-users')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Requester')
                            ->icon('heroicon-o-user')
                            ->placeholder('Unknown')
                            ->formatStateUsing(function ($state, $record) {
                                return $record->user ? $record->user->name : 'Unknown';
                            }),

                        TextEntry::make('user.email')
                            ->label('Requester Email')
                            ->icon('heroicon-o-envelope')
                            ->placeholder('Unknown')
                            ->formatStateUsing(function ($state, $record) {
                                return $record->user ? $record->user->email : 'Unknown';
                            }),
                    ]),

                // Timeline Section
                Section::make('Timeline')
                    ->description('Important dates and times')
                    ->icon('heroicon-o-clock')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created')
                            ->dateTime('M j, Y g:i A')
                            ->icon('heroicon-o-calendar')
                            ->timezone('America/New_York'),

                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('M j, Y g:i A')
                            ->icon('heroicon-o-arrow-path')
                            ->timezone('America/New_York'),

                        TextEntry::make('closed_at')
                            ->label('Closed At')
                            ->dateTime('M j, Y g:i A')
                            ->icon('heroicon-o-check-circle')
                            ->placeholder('Still Open')
                            ->timezone('America/New_York'),
                    ]),

                // Comments Section (only for admin view)
                Section::make('Comments & Activity')
                    ->description('Conversation history and updates')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        RepeatableEntry::make('comments')
                            ->label('')
                            ->columnSpanFull()
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('user.name')
                                            ->label('Author')
                                            ->badge()
                                            ->color(fn ($record) => $record->user->hasRole(['super_admin', 'admin', 'agent']) ? 'info' : 'primary')
                                            ->formatStateUsing(function ($state, $record) {
                                                $name = $state ?? 'Unknown';
                                                if ($record->user->hasRole(['super_admin', 'admin', 'agent'])) {
                                                    return "👤 {$name}";
                                                }
                                                return "👥 {$name}";
                                            }),

                                        TextEntry::make('created_at')
                                            ->label('Posted')
                                            ->dateTime('M j, Y g:i A')
                                            ->size('text-xs'),
                                    ]),

                                TextEntry::make('content')
                                    ->label('Message')
                                    ->markdown()
                                    ->columnSpanFull()
                                    ->extraAttributes(fn ($record) => $record->is_internal ? [
                                        'class' => 'bg-orange-50 border-l-4 border-orange-200 p-3 rounded'
                                    ] : []),

                                TextEntry::make('is_internal')
                                    ->label('')
                                    ->visible(fn ($record) => $record->is_internal)
                                    ->badge()
                                    ->color('warning')
                                    ->formatStateUsing('Internal Note')
                                    ->icon('heroicon-o-eye-slash'),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
