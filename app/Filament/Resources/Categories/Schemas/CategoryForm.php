<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Category Name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., Technical Support, Billing, Feature Request')
                    ->helperText('Enter a descriptive name for this category'),

                TextInput::make('slug')
                    ->label('URL Slug')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., technical-support')
                    ->unique(ignoreRecord: true)
                    ->helperText('Used in URLs and API references. Should be lowercase with hyphens.'),

                Select::make('color')
                    ->label('Display Color')
                    ->options([
                        'primary' => 'Primary',
                        'secondary' => 'Secondary',
                        'success' => 'Success',
                        'danger' => 'Danger',
                        'warning' => 'Warning',
                        'info' => 'Info',
                        'gray' => 'Gray',
                    ])
                    ->default('info')
                    ->required()
                    ->helperText('Color used for badges and UI elements'),
            ]);
    }
}
