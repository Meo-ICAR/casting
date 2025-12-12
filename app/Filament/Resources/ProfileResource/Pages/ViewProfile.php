<?php

namespace App\Filament\Resources\ProfileResource\Pages;

use App\Filament\Resources\ProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProfile extends ViewRecord
{
    protected static string $resource = ProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
            Action::make('view_roles')
            ->label('Ruoli disponibili')
            ->url(fn ($record) => static::getUrl('roles', ['record' => $record]))
            ->icon('heroicon-o-user-group'),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Trasforma i dati JSON in un formato leggibile per la visualizzazione
        if (isset($data['appearance']) && is_string($data['appearance'])) {
            $data['appearance'] = json_decode($data['appearance'], true);
        }

        if (isset($data['capabilities']) && is_string($data['capabilities'])) {
            $data['capabilities'] = json_decode($data['capabilities'], true);
        }

        if (isset($data['measurements']) && is_string($data['measurements'])) {
            $data['measurements'] = json_decode($data['measurements'], true);
        }

        if (isset($data['socials']) && is_string($data['socials'])) {
            $data['socials'] = json_decode($data['socials'], true);
        }

        return $data;
    }
}
