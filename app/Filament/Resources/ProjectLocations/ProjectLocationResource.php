<?php
// In ProjectLocationResource.php
namespace App\Filament\Resources\ProjectLocations;

use App\Filament\Resources\ProjectLocations\Pages\CreateProjectLocation;
use App\Filament\Resources\ProjectLocations\Pages\EditProjectLocation;
use App\Filament\Resources\ProjectLocations\Pages\ListProjectLocations;
use App\Filament\Resources\ProjectLocations\Pages\ViewProjectLocation;
use App\Filament\Resources\ProjectLocations\Schemas\ProjectLocationForm;
use App\Filament\Resources\ProjectLocations\Tables\ProjectLocationsTable;
use App\Models\ProjectLocation;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProjectLocationResource extends Resource
{
    protected static ?string $model = ProjectLocation::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $navigationLabel = 'Set';
    protected static ?string $modelLabel = 'Set';
    protected static ?string $pluralModelLabel = 'Sets';
    protected static UnitEnum|string|null $navigationGroup = 'Produzione';
    protected static ?int $navigationSort = 4;


    public static function form(Schema $schema): Schema
    {
        return ProjectLocationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectLocationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjectLocations::route('/'),
            'create' => CreateProjectLocation::route('/create'),
            'edit' => EditProjectLocation::route('/{record}/edit'),
            'view' => ViewProjectLocation::route('/{record}'),
        ];
    }
}
