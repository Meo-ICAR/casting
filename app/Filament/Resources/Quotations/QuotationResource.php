<?php

namespace App\Filament\Resources\Quotations;

use App\Filament\Resources\Quotations\Pages\CreateQuotation;
use App\Filament\Resources\Quotations\Pages\EditQuotation;
use App\Filament\Resources\Quotations\Pages\FillQuotation;
use App\Filament\Resources\Quotations\Pages\ListQuotations;
use App\Filament\Resources\Quotations\Schemas\FillQuotationForm;
use App\Filament\Resources\Quotations\Schemas\QuotationForm;
use App\Filament\Resources\Quotations\Tables\QuotationsTable;
use App\Models\Project;
use App\Models\Quotation;
use App\Models\Service;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;


class QuotationResource extends Resource
{
    protected static ?string $model = Quotation::class;

    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedWrenchScrewdriver;
    protected static ?string $navigationLabel = 'Preventivi';
    protected static ?string $modelLabel = 'Preventivo';
    protected static ?string $pluralModelLabel = 'Preventivi';
    protected static UnitEnum|string|null $navigationGroup = 'In lavorazione';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        if (request()->routeIs('filament.admin.resources.quotations.fill')) {
            return FillQuotationForm::configure($schema);
        }
        return QuotationForm::configure($schema);
    }

      public static function table(Table $table): Table
    {
        return QuotationsTable::configure($table);
    }
    public static function getRelations(): array
    {
        return [];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'projectService.project',
                'projectService.serviceType',
                'service'
            ])
            ->latest();
    }
    public static function getPages(): array
    {
        return [
            'index' => ListQuotations::route('/'),
            'create' => CreateQuotation::route('/create'),
            'edit' => EditQuotation::route('/{record}/edit'),
            'fill' => FillQuotation::route('/{record}/fill'),
        ];
    }
    /*
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
        */
    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
