<?php

namespace App\Filament\Resources\Tickets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class TicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('subject')
                    ->label('Subject')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->tooltip(fn (TextColumn $column): ?string => $column->getState()),

                BadgeColumn::make('priority')
                    ->label('Priority')
                    ->colors([
                        'success' => 'Low',
                        'warning' => 'Medium',
                        'danger' => 'High',
                        'danger' => 'Urgent',
                    ])
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Requester')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Unknown'),

                TextColumn::make('agent.name')
                    ->label('Agent')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Unassigned'),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn ($record) => $record->category->color ?? 'gray'),

                TextColumn::make('status.name')
                    ->label('Status')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                IconColumn::make('closed_at')
                    ->label('Closed')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('warning'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->description(fn ($record): string => $record->created_at->diffForHumans()),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('priority')
                    ->options([
                        'Low' => 'Low',
                        'Medium' => 'Medium',
                        'High' => 'High',
                        'Urgent' => 'Urgent',
                    ]),

                SelectFilter::make('status')
                    ->relationship('status', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('agent')
                    ->relationship('agent', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Assigned Agent'),

                Filter::make('closed')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('closed_at'))
                    ->label('Closed Tickets'),

                Filter::make('open')
                    ->query(fn (Builder $query): Builder => $query->whereNull('closed_at'))
                    ->label('Open Tickets'),

                Filter::make('unassigned')
                    ->query(fn (Builder $query): Builder => $query->whereNull('agent_id'))
                    ->label('Unassigned Tickets'),
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
            ->emptyStateHeading('No tickets found')
            ->emptyStateDescription('Create your first support ticket to get started.')
            ->emptyStateActions([
                \Filament\Actions\CreateAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
