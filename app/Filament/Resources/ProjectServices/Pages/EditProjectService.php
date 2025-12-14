<?php

namespace App\Filament\Resources\ProjectServices\Pages;

use App\Filament\Resources\ProjectServices\ProjectServiceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProjectService extends EditRecord
{
    protected static string $resource = ProjectServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
