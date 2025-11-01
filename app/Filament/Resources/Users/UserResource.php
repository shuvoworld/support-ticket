<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Models\User;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use App\Models\Department;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Users';

    protected static ?string $modelLabel = 'User';

    protected static ?string $pluralModelLabel = 'Users';

    protected static ?int $navigationSort = 3;

    // protected static string|UnitEnum|null $navigationGroup = 'Organization Management';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(fn ($state) => $state ? Hash::make($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->rule(Password::default()),

                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->visible(fn () => auth()->user()->hasPermissionTo('role_view_any')),

                CheckboxList::make('departments')
                    ->relationship('departments', 'name')
                    ->options(fn () => Department::where('is_active', true)->pluck('name', 'id'))
                    ->searchable()
                    ->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin']))
                    ->helperText('Select departments for this agent. Users will only see tickets from their assigned departments.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('roles.name')
                    ->label('Roles')
                    ->colors([
                        'danger' => 'super_admin',
                        'warning' => 'admin',
                        'success' => 'agent',
                        'info' => 'user',
                    ])
                    ->separator(','),

                TextColumn::make('departments.name')
                    ->label('Departments')
                    ->badge()
                    ->separator(', ')
                    ->limitList(1)
                    ->expandableLimitedList()
                    ->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin'])),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }

    public static function getGlobalSearchResultTitle($record): string
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Email' => $record->email,
            'Roles' => $record->roles->pluck('name')->implode(', '),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasPermissionTo('user_view_any');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasPermissionTo('user_create');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->hasPermissionTo('user_update');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->hasPermissionTo('user_delete');
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()->hasPermissionTo('user_view');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyPermission(['user_view_any', 'user_create', 'user_update', 'user_delete']);
    }
}
