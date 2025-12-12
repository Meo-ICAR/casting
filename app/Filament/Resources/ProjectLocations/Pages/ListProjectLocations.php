<?php

namespace App\Filament\Resources\ProjectLocations\Pages;

use App\Filament\Resources\ProjectLocations\ProjectLocationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProjectLocations extends ListRecords
{
    protected static string $resource = ProjectLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
