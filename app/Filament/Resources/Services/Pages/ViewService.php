<?php

namespace App\Filament\Resources\Services\Pages;

use App\Filament\Resources\Services\ServiceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;

class ViewService extends ViewRecord
{
    protected static string $resource = ServiceResource::class;

 protected function getHeaderActions(): array
    {
        return [
             Action::make('projectServices')
                ->label('Richieste')
                  ->color('successs')
                ->icon('heroicon-o-document-chart-bar')
                 ->url(fn ($record) => static::$resource::getUrl('project-services', ['record' => $record])),
            EditAction::make(),
          //  DeleteAction::make(),
        ];
    }
}
