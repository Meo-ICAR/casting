<?php

namespace App\Filament\Resources\ProjectLocations\Pages;

use App\Filament\Resources\ProjectLocations\ProjectLocationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProjectLocation extends EditRecord
{
    protected static string $resource = ProjectLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
