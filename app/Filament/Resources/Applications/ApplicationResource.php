<?php

namespace App\Filament\Resources\Applications;

use App\Filament\Resources\Applications\Pages\CreateApplication;
use App\Filament\Resources\Applications\Pages\EditApplication;
use App\Filament\Resources\Applications\Pages\ListApplications;
use App\Filament\Resources\Applications\Pages\ViewApplication;
use App\Filament\Resources\Applications\Schemas\ApplicationForm;
use App\Filament\Resources\Applications\Schemas\ApplicationInfolist;
use App\Filament\Resources\Applications\Tables\ApplicationsTable;
use App\Models\Application;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Console\Commands\SendWhatsAppMessages;
use Filament\Notifications\Notification;
class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

   protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Candidature';
    protected static ?string $modelLabel = 'Candidatura';
    protected static ?string $pluralModelLabel = 'Candidature';
    protected static UnitEnum|string|null $navigationGroup = 'In lavorazione';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return ApplicationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ApplicationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApplicationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->with(['role.project', 'profile.user', 'profile.media']);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApplications::route('/'),
            'kanban' => \App\Filament\Resources\Applications\Pages\ApplicationsKanban::route('/kanban'),
            'kanban.role' => \App\Filament\Resources\Applications\Pages\ApplicationsKanban::route('/kanban/{role}'),
            'create' => CreateApplication::route('/create'),
            'view' => ViewApplication::route('/{record}'),
            'edit' => EditApplication::route('/{record}/edit'),
        ];
    }

    public static function getNavigationItems(): array
    {
        $items = parent::getNavigationItems();

        // Add Kanban view as a sub-item
        /*
        $items[] = \Filament\Navigation\NavigationItem::make('kanban')
            ->label('Vista Kanban')
            ->icon('heroicon-o-view-columns')
            ->group('Produzione')
            ->sort(4.5)
            ->url(static::getUrl('kanban'));
            */

        return $items;
    }
}
