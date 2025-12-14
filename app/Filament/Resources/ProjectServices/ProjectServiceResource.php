<?php

namespace App\Filament\Resources\ProjectServices;

use App\Filament\Resources\ProjectServices\Pages\CreateProjectService;
use App\Filament\Resources\ProjectServices\Pages\EditProjectService;
use App\Filament\Resources\ProjectServices\Pages\ListProjectServices;
use App\Filament\Resources\ProjectServices\Schemas\ProjectServiceForm;
use App\Filament\Resources\ProjectServices\Tables\ProjectServiceTable;
use App\Models\ProjectService;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProjectServiceResource extends Resource
{
    protected static ?string $model = ProjectService::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $navigationLabel = 'Servizio';
    protected static ?string $modelLabel = 'Servizio';
    protected static ?string $pluralModelLabel = 'Servizi richiesti';
    protected static UnitEnum|string|null $navigationGroup = 'Produzione';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return ProjectServiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectServiceTable::configure($table);
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
            'index' => ListProjectServices::route('/'),
            'create' => CreateProjectService::route('/create'),
            'edit' => EditProjectService::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
