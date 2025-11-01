<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Models\Department;
use Filament\Actions;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BackedEnum;
use UnitEnum;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Departments';

    protected static ?string $modelLabel = 'Department';

    protected static ?string $pluralModelLabel = 'Departments';

    protected static ?int $navigationSort = 2;

    // protected static string|UnitEnum|null $navigationGroup = 'Organization Management';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Section::make('Department Information')
                    ->description('Manage department details and settings')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Department Name')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Enter department name'),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->placeholder('Enter department description'),

                        Forms\Components\Select::make('manager_id')
                            ->label('Department Manager')
                            ->relationship('manager')
                            ->searchable()
                            ->preload()
                            ->placeholder('Select department manager')
                            ->helperText('This user will be designated as the department manager'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Inactive departments will not appear in dropdowns for users'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('manager.name')
                    ->label('Manager')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->placeholder('No manager assigned'),

                Tables\Columns\TextColumn::make('user_count')
                    ->label('Users')
                    ->alignCenter()
                    ->sortable()
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('ticket_count')
                    ->label('Tickets')
                    ->alignCenter()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ]),

                Tables\Filters\SelectFilter::make('manager_id')
                    ->label('Manager')
                    ->relationship('manager', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ])
            ->emptyStateHeading('No departments found')
            ->emptyStateDescription('Create your first department to get started.')
            ->emptyStateActions([
                CreateAction::make()->label('Create Department'),
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
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'view' => Pages\ViewDepartment::route('/{record}'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyPermission(['department_view_any', 'department_view']);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasAnyPermission(['department_create']);
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->hasAnyPermission(['department_edit', 'department_update']);
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->hasAnyPermission(['department_delete']);
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()->hasAnyPermission(['department_delete']);
    }

    public static function canForceDelete($record): bool
    {
        return auth()->user()->hasAnyPermission(['department_force_delete']);
    }

    public static function canForceDeleteAny(): bool
    {
        return auth()->user()->hasAnyPermission(['department_force_delete']);
    }

    public static function canRestore($record): bool
    {
        return auth()->user()->hasAnyPermission(['department_restore']);
    }

    public static function canRestoreAny(): bool
    {
        return auth()->user()->hasAnyPermission(['department_restore']);
    }

    public static function canReorder(): bool
    {
        return false;
    }

    public static function canReplicate($record): bool
    {
        return false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyPermission(['department_view_any', 'department_create', 'department_update', 'department_delete']);
    }
}