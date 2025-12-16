<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\Pages\CreateService;
use App\Filament\Resources\Services\Pages\EditService;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Filament\Resources\Services\Pages\ViewService;
use App\Filament\Resources\Services\Schemas\ServiceForm;
use App\Filament\Resources\Services\Schemas\ServiceInfolist;
use App\Filament\Resources\Services\Tables\ServicesTable;
use App\Models\Service;
use App\Models\ServiceType;
use App\Filament\Resources\Services\Pages\ServiceProjectServices;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;
    protected static ?string $navigationLabel = 'Servizi';
    protected static ?string $modelLabel = 'Servizio';
    protected static ?string $pluralModelLabel = 'Servizi';
   // protected static UnitEnum|string|null $navigationGroup = 'Database';
    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
{
    return 'Database'; // The name of the group
}
public static function shouldRegisterNavigation(): bool
{
    return auth()->user()?->isServicer();
}

    public static function form(Schema $schema): Schema
    {
        return ServiceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ServiceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServicesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
        //     \App\Filament\Resources\ServiceResource\RelationManagers\ProjectServicesRelationManager::class,
        ];

    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery()
        ->with(['serviceType','user']);
    // If user is a servicer, only show services from their company
    if (auth()->user()->isServicer() && !auth()->user()->isAdmin()) {
        $query->where('company_id', auth()->user()->company_id);
    }
     return $query; // Add this line to return the query

    }

    public static function getPages(): array
    {
        return [
            'index' => ListServices::route('/'),
            'create' => CreateService::route('/create'),
            'view' => ViewService::route('/{record}'),
            'edit' => EditService::route('/{record}/edit'),
      'project-services' => ServiceProjectServices::route('/{record}/project-services'),


        ];
    }

    public static function canViewAny(): bool
{
    return auth()->user()?->isServicer();
}

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
