<?php

namespace App\Filament\Resources\Quotations\Pages;

use App\Filament\Resources\Quotations\QuotationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class FillQuotation extends EditRecord
{
    protected static string $resource = QuotationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        // Get the previous URL from the session or fallback to the index
        $previousUrl = session()->previousUrl();

        // If we have a previous URL and it's not the current URL, go back to it
        if ($previousUrl && $previousUrl !== url()->current()) {
            return $previousUrl;
        }

        // Fallback to the quotations index
        return $this->getResource()::getUrl('index');
    }
}
