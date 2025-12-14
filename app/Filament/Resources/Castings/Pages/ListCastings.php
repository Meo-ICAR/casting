<?php

namespace App\Filament\Resources\Castings\Pages;

use App\Filament\Resources\Castings\CastingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCastings extends ListRecords
{
    protected static string $resource = CastingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
