<?php
// In ProjectLocationResource.php
namespace App\Filament\Resources\ProjectLocations;

use App\Filament\Resources\ProjectLocations\Pages\CreateProjectLocation;
use App\Filament\Resources\ProjectLocations\Pages\EditProjectLocation;
use App\Filament\Resources\ProjectLocations\Pages\ListProjectLocations;
use App\Filament\Resources\ProjectLocations\Pages\ViewProjectLocation;
use App\Filament\Resources\ProjectLocations\Schemas\ProjectLocationForm;
use App\Filament\Resources\ProjectLocations\Schemas\ProjectLocationInfolist;
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

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    protected static ?string $navigationLabel = 'Location';
    protected static ?string $modelLabel = 'Location';
    protected static ?string $pluralModelLabel = 'Locations';
    protected static UnitEnum|string|null $navigationGroup = 'Produzione';
    protected static ?int $navigationSort = 3;


    public static function form(Schema $schema): Schema
    {
        return ProjectLocationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectLocationsTable::configure($table);
    }
}
