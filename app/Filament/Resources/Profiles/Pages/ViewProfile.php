<?php

namespace App\Filament\Resources\Profiles\Pages;

use App\Filament\Resources\Profiles\ProfileResource;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;

class ViewProfile extends ViewRecord
{
    protected static string $resource = ProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
             Action::make('roles')
                ->label('Ruoli disponibili')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->url(fn () => static::$resource::getUrl('roles', ['record' => $this->getRecord()])),
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}
