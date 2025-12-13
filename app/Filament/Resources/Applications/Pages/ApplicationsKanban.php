<?php

namespace App\Filament\Resources\Applications\Pages;

use App\Enums\ApplicationStatus;
use App\Filament\Resources\Applications\ApplicationResource;
use App\Models\Application;
use App\Models\Role;
use Filament\Actions\CreateAction;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\BulkAction;
use BackedEnum;
use UnitEnum;
use Filament\Support\Icons\Heroicon;

class ApplicationsKanban extends ListRecords
{
    use InteractsWithTable;

    protected static string $resource = ApplicationResource::class;
    protected static ?string $title = 'Kanban Candidature';

    public ?Role $role = null;

    public function mount($roleId = null): void
    {
        if ($roleId) {
            $this->role = Role::findOrFail($roleId);
            static::$title = 'Kanban Candidature - ' . $this->role->name;
        } elseif (isset($this->record)) {
            $this->role = Role::findOrFail($this->record);
            static::$title = 'Kanban Candidature - ' . $this->role->name;
        }

        parent::mount();
    }

    // Definisci gli stati (colonne) della tua board
    protected static array $statuses = [
        'disponibilita' => 'In Attesa',
        'pending' => 'In Corso',
        'under_review' => 'Completato',
    ];

    // Restituisci il campo del modello che contiene lo stato
    protected function statuses(): array
    {
        return static::$statuses;
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
            ->defaultGroup('status')
            ->defaultSort('created_at', 'desc')
            ->columns([
                \Filament\Tables\Columns\Layout\View::make('filament.tables.components.application-kanban-card')
                    ->viewData([
                        /*
                        'viewAction' => ViewAction::make()
                            ->url(fn (Application $record): string => ApplicationResource::getUrl('view', ['record' => $record])),
                        'editAction' => EditAction::make()
                            ->url(fn (Application $record): string => ApplicationResource::getUrl('edit', ['record' => $record])),
                        'deleteAction' => DeleteAction::make()
                            ->url(fn (Application $record): string => ApplicationResource::getUrl('delete', ['record' => $record])),
                        */
                    ])
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
                '2xl' => 4,
            ])
            ->filters([
                // Add any filters you need
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
