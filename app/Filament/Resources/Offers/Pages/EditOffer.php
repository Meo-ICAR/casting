<?php

namespace App\Filament\Resources\Offers\Pages;

use App\Filament\Resources\OfferResource;
use Filament\Resources\Pages\EditRecord;

class EditOffer extends EditRecord
{
    protected static string $resource = OfferResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
