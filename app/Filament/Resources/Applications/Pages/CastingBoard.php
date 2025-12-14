<?php

namespace App\Filament\Pages;

use App\Models\Application;
use App\Enums\ApplicationStatus; // Assicurati che il tuo Enum esista
use Illuminate\Support\Collection;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CastingBoard  extends Resource
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $navigationLabel = 'Casting';
    protected static UnitEnum|string|null $navigationGroup = 'Produzione';
    protected static ?string $title = 'Kanban Board';

    // Collega la vista che modificheremo dopo
    protected static string $view = 'filament.pages.casting-board';

    // Le colonne della Kanban
    public function getStatuses(): array
    {
        return [
            'pending' => ['title' => 'Nuovi', 'color' => 'bg-gray-100 border-gray-200'],
            'invited' => ['title' => 'Invitati', 'color' => 'bg-blue-50 border-blue-200'],
            'audition' => ['title' => 'In Provino', 'color' => 'bg-yellow-50 border-yellow-200'],
            'callback' => ['title' => 'Callback', 'color' => 'bg-orange-50 border-orange-200'],
            'shortlisted' => ['title' => 'Selezionati', 'color' => 'bg-green-50 border-green-200'],
            'rejected' => ['title' => 'Scartati', 'color' => 'bg-red-50 border-red-200'],
        ];
    }

    // Carica le candidature raggruppate per stato
    public function getRecords(): Collection
    {
        return Application::query()
            ->with(['profile.media', 'role.project'])
            ->get()
            ->groupBy('status.value');
    }

    // Questa funzione viene chiamata dal frontend quando rilasci una card
    public function updateStatus($recordId, $newStatus)
    {
        $application = Application::find($recordId);

        if ($application) {
            $application->update(['status' => $newStatus]);

            // Notifica di successo (Toast)
            \Filament\Notifications\Notification::make()
                ->title('Stato aggiornato')
                ->success()
                ->send();
        }
    }
}
