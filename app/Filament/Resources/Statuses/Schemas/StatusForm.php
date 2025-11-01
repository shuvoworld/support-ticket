<?php

namespace App\Filament\Resources\Statuses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StatusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Status Name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., Open, In Progress, Resolved, Closed')
                    ->helperText('This will be displayed to users and agents')
                    ->unique(ignoreRecord: true),
            ]);
    }
}
