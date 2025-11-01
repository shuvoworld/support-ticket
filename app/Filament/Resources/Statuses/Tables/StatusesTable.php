<?php

namespace App\Filament\Resources\Statuses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;

class StatusesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Status Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                BadgeColumn::make('tickets_count')
                    ->label('Tickets')
                    ->counts('tickets')
                    ->color('primary')
                    ->alignCenter(),

                IconColumn::make('tickets_count')
                    ->label('Active')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->tickets()->exists())
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->alignCenter(),

                TextColumn::make('tickets.last.created_at')
                    ->label('Last Used')
                    ->dateTime('M j, Y')
                    ->getStateUsing(fn ($record) => $record->tickets()->latest()->first()?->created_at)
                    ->placeholder('Never used')
                    ->sortable(false),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                if ($record->tickets()->exists()) {
                                    // Prevent deletion of statuses with tickets
                                    throw new \Exception("Cannot delete status '{$record->name}' because it is assigned to {$record->tickets()->count()} ticket(s).");
                                }
                            }
                        }),
                ]),
            ])
            ->emptyStateHeading('No statuses found')
            ->emptyStateDescription('Create your first status to start tracking ticket progress.')
            ->emptyStateActions([
                \Filament\Actions\CreateAction::make(),
            ])
            ->defaultSort('name', 'asc');
    }
}
