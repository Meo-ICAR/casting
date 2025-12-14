<?php

namespace App\Filament\Resources\Profiles\ProfileResource\Pages;

use App\Filament\Resources\Profiles\ProfileResource;
use App\Models\Application;
use App\Models\Role;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\BulkAction;

class ProfileRoles extends ListRecords
{
    protected static string $resource = ProfileResource::class;
     protected static ?string $title = 'Ruoli Disponibili';
     protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;


    public $profile;
public function mount($record = null): void
{
    if ($record) {
        $this->profile = \App\Models\Profile::findOrFail($record);
    } else {
        $this->profile = \App\Models\Profile::findOrFail($this->record);
    }
    parent::mount();
}
    public function getTitle(): string
    {
        return 'Ruoli Disponibili ' . $this->profile->stage_name;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->profile->getMatchingRolesQuery())
            ->recordUrl(null) // This disables the default record URL
            ->recordAction('apply')
            ->columns([

                Tables\Columns\TextColumn::make('project.title')
                    ->label('Progetto')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Ruolo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('city')
                    ->label('Città')
                    ->searchable()
                    ->sortable(),
             TextColumn::make('start_date')
                    ->label('Dal')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Al')
                    ->date()
                    ->sortable(),

                TextColumn::make('application_status')
    ->label('Candidatura')
     ->badge()
    ->state(function ($record) {
        return $this->getApplicationStatus($record);
    })
    ->color(function ($record) {
        return $this->getStatusColor($record);
    })

            ])
            ->actions([
                  \Filament\Actions\Action::make('apply')
                ->label(fn (Role $record) => $this->hasApplied($record) ? 'Già Candidato' : 'Candidati')

                      ->button()
        ->color(fn (Role $record) => $this->hasApplied($record) ? 'gray' : 'success')
        ->icon('heroicon-o-check-circle')
                    ->action(function (Role $record) {
                    if (!$this->hasApplied($record)) {
                        Application::create([
                            'profile_id' => $this->profile->id,
                            'role_id' => $record->id,
                            'status' => \App\Enums\ApplicationStatus::PENDING,
                        ]);
                        $this->dispatch('refresh');
                    }

  })
             ->disabled(fn (Role $record) => $this->hasApplied($record))

])

->toolbarActions([
                        // 5. AZIONI DI GRUPPO (BULK)
               BulkActionGroup::make([
        BulkAction::make('apply_bulk')
            ->label('Invia Candidature')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                foreach ($records as $record) {
                    if (!$this->hasApplied($record)) {
                        Application::create([
                            'profile_id' => $this->profile->id,
                            'role_id' => $record->id,
                            'status' => \App\Enums\ApplicationStatus::PENDING,
                        ]);
                    }
                }
                $this->dispatch('refresh');
            })
            ->deselectRecordsAfterCompletion()
              ])
    ])
            ;
    }

protected function hasApplied(Role $record): bool
{
    return $this->profile->applications()
        ->where('role_id', $record->id)
        ->where('profile_id', $this->profile->id)
        ->exists();
}

protected function getApplicationStatus($record): string
{

    if (!$this->hasApplied($record)) {
        return '---';
    }
    $application = $this->profile->applications()
        ->where('role_id', $record->id)
        ->where('profile_id', $this->profile->id)
        ->first();
    return $application ?  $application->getStatusLabel()  :null;

}
protected function getStatusColor($record): string
{

    if (!$this->hasApplied($record)) {
        return 'gray';
    }
    $application = $this->profile->applications()
        ->where('role_id', $record->id)
        ->where('profile_id', $this->profile->id)
        ->first();
     return $application ? $application->getStatusColor() : 'gray';
}


// Temporary debug method - remove after debugging
protected function debugApplicationStatus($record): void
{
    $application = $this->profile->applications()
        ->where('role_id', $record->id)
        ->where('profile_id', $this->profile->id)
        ->first();
    \Log::info('Debug Application Status', [
        'profile_id' => $this->profile->id,
        'role_id' => $record->id,
        'application' => $application,
        'status' => $application ? $application->status : 'null',
        'status_type' => $application ? gettype($application->status) : 'null',

    ]);
}
}
