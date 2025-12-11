<?php

namespace App\Filament\Resources\Castings\Pages;

use App\Filament\Resources\Castings\CastingResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCasting extends ViewRecord
{
    protected static string $resource = CastingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
