<?php

namespace App\Filament\Resources\Comments;

use App\Filament\Resources\Comments\Pages\CreateComment;
use App\Filament\Resources\Comments\Pages\EditComment;
use App\Filament\Resources\Comments\Pages\ListComments;
use App\Filament\Resources\Comments\Pages\ViewComment;
use App\Filament\Resources\Comments\Schemas\CommentForm;
use App\Filament\Resources\Comments\Schemas\CommentInfolist;
use App\Filament\Resources\Comments\Tables\CommentsTable;
use App\Models\Comment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $navigationLabel = 'Comments';

    protected static ?string $modelLabel = 'Comment';

    protected static ?string $pluralModelLabel = 'Comments';

    // protected static ?string $navigationGroup = 'Support Management'; // Navigation groups not supported in this version

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'content';

    // protected static ?string $activeNavigationIcon = Heroicon::SolidChatBubbleLeftRight; // Not supported in this version

    public static function form(Schema $schema): Schema
    {
        return CommentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CommentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CommentsTable::configure($table);
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
            'index' => ListComments::route('/'),
            'create' => CreateComment::route('/create'),
            'view' => ViewComment::route('/{record}'),
            'edit' => EditComment::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['content', 'user.name'];
    }

    public static function getGlobalSearchResultTitle($record): string
    {
        return strip_tags(substr($record->content, 0, 50)) . (strlen($record->content) > 50 ? '...' : '');
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Author' => $record->user?->name ?? 'Unknown',
            'Type' => $record->is_internal ? 'Internal Note' : 'Public Comment',
            'Ticket' => $record->ticket?->subject ?? 'Unknown',
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyPermission(['comment_view_any', 'comment_view_own']);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasPermissionTo('comment_create');
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(!auth()->user()->hasPermissionTo('comment_view_any'), function (Builder $query) {
                // Users can only see their own comments and comments on their tickets
                $query->where(function (Builder $query) {
                    $query->where('user_id', auth()->id())
                        ->orWhereHas('ticket', function (Builder $query) {
                            $query->where('user_id', auth()->id());
                        });
                });
            });
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyPermission(['comment_view_any', 'comment_view_own', 'comment_create', 'comment_update', 'comment_delete']);
    }
}
