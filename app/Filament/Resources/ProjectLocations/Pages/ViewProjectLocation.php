<?php

namespace App\Filament\Resources\ProjectLocations\Pages;

use App\Filament\Resources\ProjectLocations\ProjectLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProjectLocation extends ViewRecord
{
    protected static string $resource = ProjectLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
