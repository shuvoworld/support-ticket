<?php

namespace App\Filament\Resources\Statuses;

use App\Filament\Resources\Statuses\Pages\CreateStatus;
use App\Filament\Resources\Statuses\Pages\EditStatus;
use App\Filament\Resources\Statuses\Pages\ListStatuses;
use App\Filament\Resources\Statuses\Pages\ViewStatus;
use App\Filament\Resources\Statuses\Schemas\StatusForm;
use App\Filament\Resources\Statuses\Schemas\StatusInfolist;
use App\Filament\Resources\Statuses\Tables\StatusesTable;
use App\Models\Status;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class StatusResource extends Resource
{
    protected static ?string $model = Status::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFlag;

    protected static ?string $navigationLabel = 'Statuses';

    protected static ?string $modelLabel = 'Status';

    protected static ?string $pluralModelLabel = 'Statuses';

    // protected static string|UnitEnum|null $navigationGroup = 'Ticket Management';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    // protected static ?string $activeNavigationIcon = Heroicon::SolidFlag; // Not supported in this version

    public static function form(Schema $schema): Schema
    {
        return StatusForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StatusInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StatusesTable::configure($table);
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
            'index' => ListStatuses::route('/'),
            'create' => CreateStatus::route('/create'),
            'view' => ViewStatus::route('/{record}'),
            'edit' => EditStatus::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function getGlobalSearchResultTitle($record): string
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Type' => 'Status',
            'Tickets' => $record->tickets_count ?? 0,
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasPermissionTo('status_view_any');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasPermissionTo('status_create');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('update', $record);
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete', $record);
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()->can('view', $record);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyPermission(['status_view_any', 'status_create', 'status_update', 'status_delete']);
    }
}
