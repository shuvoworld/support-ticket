<?php

namespace App\Filament\Resources\Comments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class CommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket.subject')
                    ->label('Ticket')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->ticket->subject)
                    ->badge()
                    ->color('primary'),

                TextColumn::make('content')
                    ->label('Comment')
                    ->searchable()
                    ->limit(50)
                    ->markdown()
                    ->tooltip(fn (TextColumn $column): ?string => strip_tags($column->getState())),

                TextColumn::make('user.name')
                    ->label('Author')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn ($record) => $record->is_internal ? 'warning' : 'success'),

                BadgeColumn::make('is_internal')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => $state ? 'Internal' : 'Public')
                    ->color(fn ($state) => $state ? 'warning' : 'success'),

                TextColumn::make('created_at')
                    ->label('Posted')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->description(fn ($record): string => $record->created_at->diffForHumans())
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('ticket')
                    ->relationship('ticket', 'subject')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Author'),

                Filter::make('internal')
                    ->query(fn (Builder $query): Builder => $query->where('is_internal', true))
                    ->label('Internal Notes Only'),

                Filter::make('public')
                    ->query(fn (Builder $query): Builder => $query->where('is_internal', false))
                    ->label('Public Comments Only'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No comments found')
            ->emptyStateDescription('Comments will appear here once they are added to tickets.')
            ->emptyStateActions([
                \Filament\Actions\CreateAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
