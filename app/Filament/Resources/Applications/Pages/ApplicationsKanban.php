<?php

namespace App\Filament\Resources\Applications\Pages;

use App\Enums\ApplicationStatus;
use Filament\Support\Enums\FontWeight;
use App\Filament\Resources\Applications\ApplicationResource;
use App\Models\Application;
use App\Models\Role;
use Filament\Actions\CreateAction;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\BulkAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Select;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Filament\Actions\BulkActionGroup;
use BackedEnum as BaseBackedEnum;
use UnitEnum as BaseUnitEnum;
use Filament\Support\Icons\Heroicon;
use App\Console\Commands\SendWhatsAppMessages;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;

class ApplicationsKanban extends ListRecords
{
    use InteractsWithTable;

    protected static string $resource = ApplicationResource::class;
    protected static ?string $title = 'Candidature';

    public ?Role $role = null;

    public function mount($roleId = null): void
    {

 if ($roleId) {
        $this->role = Role::findOrFail($roleId);
    } elseif (isset($this->record)) {
        $this->role = Role::findOrFail($this->record);
    }
    if ($this->role) {
        static::$title = 'Candidatura - N. ' . $this->role->n . ' '.$this->role->name . ' (' . $this->role->project->title . ')';
    }
    parent::mount();

    }

    // Definisci gli stati (colonne) della tua board
    protected array $statuses = [
        'disponibilita' => 'In Attesa',
        'pending' => 'In Corso',
        'under_review' => 'Completato',
    ];



    // Restituisci il campo del modello che contiene lo stato
    protected function statuses(): array
    {
        return $this->statuses;
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery()
            ->with(['profile', 'role.project']);

        if ($this->role) {
            $query->where('role_id', $this->role->id);
        }

        return $query;
    }

    public function table(Table $table): Table
    {
        return $table
            ->selectable()
            ->defaultGroup('status')
            ->recordUrl(fn (Application $record): string => route('filament.admin.resources.profiles.view', $record->profile))

        ->columns([
            Split::make([
                 Stack::make([
                   // FOTO COPERTINA
                ImageColumn::make('profile.profile_photo')
                    ->label('')
                    // Usiamo la logica per prendere l'immagine convertita (thumb)
                    ->getStateUsing(fn ($record) => $record->profile->getFirstMediaUrl('headshots', 'thumb'))
                    ->height('50px')
                    ->width('100%')
                    ->extraImgAttributes(['class' => 'object-cover w-full rounded-t-xl']),

 TextColumn::make('profile.stage_name')
                ->label('')
                 ->weight(FontWeight::Bold)
                ->searchable()
                ->size('xs'),
         //  Telefono con WhatsApp
                   //       \Filament\Tables\Columns\Layout\Split::make([
                     TextColumn::make('profile.phone')
                            ->label('')
                            ->color('success')
                            ->size('xs')
                            ->url(fn ($record) => $record->profile->getWhatsappUrl('Ciao! Puoi ricontattarci?'))
                            ->icon('heroicon-o-chat-bubble-oval-left-ellipsis')
                            ->openUrlInNewTab()

                      ])
                           , ])
                ])

->contentGrid([
    'md' => 5,
    'xl' => 6,
    '2xl' => 7,
])
->recordClasses(fn ($record) => 'border border-blue-100 rounded-lg shadow-sm hover:shadow-md transition-shadow')
->filters([
    // Add any filters you need
])
            ->filters([
                // Add any filters you need
            ])
            ->recordActions([
              //  ViewAction::make(),
              //  EditAction::make(),
            ])
               ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('status_bulk')
             ->label('Cambia status')
            ->icon('heroicon-o-chevron-up-down')
            ->color('success')
            ->form([
                Select::make('status')
                    ->label('Nuovo Stato')
                    ->options(ApplicationStatus::options())
                    ->required(),
            ])
            ->action(function (Collection $records, array $data): void {
                foreach ($records as $record) {
                    $record->update(['status' => $data['status']]);
                }
            })
            ->deselectRecordsAfterCompletion()
    ])
                ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('torna_alla_lista')
                ->label('Torna alla lista')
                ->url(ApplicationResource::getUrl('index'))
                ->icon('heroicon-o-arrow-left')
                ->color('gray'),
        ];
    }

    protected function getViewData(): array
{
    $statuses = [
        'pending' => 'In Attesa',
        'in_progress' => 'In Corso',
        'under_review' => 'In Esame',
        'completed' => 'Completato',
    ];
    // Get all applications with their profile
     $applications = Application::query()
      ->select([
            'id',
            'role_id',
            'status',
            'profile_id', // Include the foreign key for the relationship
            // Add any other fields you need from the applications table
        ])
        ->with(['profile' => function($query) {
            $query->select('id', 'stage_name'); // Only select the fields you need
        }])
        ->orderBy('id')
        ->get();
    // Group by status
    $grouped = $applications->groupBy('status');
    // Initialize tasks with all statuses
    $tasks = [];
    foreach ($statuses as $statusKey => $statusName) {
        $tasks[$statusKey] = $grouped->get($statusKey, collect());
    }
    return [
        'statuses' => $statuses,
        'tasks' => $tasks,
    ];
}

    protected function getHeaderWidgets(): array
    {
        return [
            // Add any header widgets if needed
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // Add any footer widgets if needed
        ];
    }
}
