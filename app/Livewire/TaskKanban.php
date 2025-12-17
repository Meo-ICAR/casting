<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Application;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class TaskKanban extends Component
{
    /**
     * The tasks grouped by status
     *
     * @var array
     */
    public $tasks = [];

    /**
     * The available statuses for tasks
     *
     * @var array
     */
    public $statuses = [
        'pending' => 'In Attesa',
        'in_progress' => 'In Corso',
        'completed' => 'Completato',
    ];

    /**
     * Get the statuses for the view
     *
     * @return array
     */
    public function getStatusesProperty()
    {
        return $this->statuses;
    }

    public function mount()
    {
        // Initialize tasks as a collection
        $this->tasks = collect();

        // Get all applications and group by status
      $applications = Application::query()
    ->select('applications.id', 'applications.status', 'profiles.stage_name' , 'profiles.profile_photo')
    ->leftJoin('profiles', 'applications.profile_id', '=', 'profiles.id')
    ->orderBy('status')
    ->orderBy('id')
    ->get();

        // Group by status and ensure all statuses are present in the collection
        $grouped = $applications->groupBy('status');

        // Initialize tasks with all statuses, even if empty
        foreach ($this->statuses as $statusKey => $statusName) {
            $this->tasks[$statusKey] = $grouped->get($statusKey, collect());
        }

        \Log::debug('Tasks after mount:', $this->tasks->toArray());
    }

    // Metodo chiamato da JavaScript/Livewire quando una card viene droppata
    public function updateTaskStatus($taskId, $newStatus, $newOrderIds)
    {
        \Log::info('Updating task status', [
            'task_id' => $taskId,
            'new_status' => $newStatus,
            'new_order' => $newOrderIds
        ]);

        $task = Application::find($taskId);

        if ($task) {
            // Debug current task data
            \Log::info('Current task data', $task->toArray());

            // 1. Update task status
            $task->update([
                'status' => $newStatus
            ]);

            // 2. Reorder items in the new column
            $newOrderIds = array_values(array_filter($newOrderIds)); // Cleanup
            \Log::info('Processing new order', $newOrderIds);

            foreach ($newOrderIds as $index => $id) {
                // Update the position (order) of each item in the column
                $affected = Application::where('id', $id)->update(['order' => $index + 1]);
                \Log::info("Updated order for task $id", [
                    'new_order' => $index + 1,
                    'rows_affected' => $affected
                ]);
            }
        } else {
            \Log::error('Task not found', ['task_id' => $taskId]);
        }

        // Reload tasks to update the view
        $this->mount();
        \Log::info('Tasks after update', $this->tasks->toArray());
    }

    /**
     * Debug method to inspect current tasks and their order
     */
    public function debugTasks()
    {
        $debugInfo = [];

        // Get all tasks with their current status and order
        $allTasks = Application::orderBy('status')
            ->orderBy('id')
            ->get()
            ->map(function($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->stage_name,
                    'status' => $task->status,
                    'order' => $task->id,
                    'created_at' => $task->created_at
                ];
            });

        // Group by status for better readability
        $groupedTasks = $allTasks->groupBy('status');

        // Prepare debug output
        foreach ($this->statuses as $statusKey => $statusName) {
            $tasksInStatus = $groupedTasks->get($statusKey, collect())->sortBy('order');
            $debugInfo[$statusName] = $tasksInStatus->values()->all();
        }

        // Log the debug info
        \Log::debug('Current tasks state:', $debugInfo);

        // Return as JSON for easy inspection
        return response()->json([
            'success' => true,
            'data' => $debugInfo
        ]);
    }

   // In your TaskKanban component
protected function getViewData(): array
{
    // Initialize tasks if not already done
    if (!isset($this->tasks)) {
        $this->mount();
    }
    return [
        'statuses' => $this->statuses,
        'tasks' => $this->tasks,
    ];
}
public function render()
{
    return view('filament.tables.components.application-kanban-card', $this->getViewData());
}

    // Definisci come deve essere visualizzata ogni singola scheda (card)
    public function renderRecordTitle(): ?string
    {
        // Questo sarà il titolo della card.
        // $this->record si riferisce all'istanza del Modello
        return $this->record->title;
    }

    public function renderRecordDescription(): ?string
    {
        // Questo sarà il corpo/descrizione della card.
        return $this->record->description;
    }

    //

    // Metodo per gestire il drag and drop (opzionale, ma essenziale per Kanban)
    public function onStatusChange(int $recordId, string $status): void
    {
        // Aggiorna lo stato del record quando viene spostato
        static::getModel()::find($recordId)->update(['status' => $status]);
    }

    // Se vuoi anche gestire il riordino all'interno della colonna:
    public function onSortChange(int $recordId, string $status, int $newIndex): void
    {
        // Qui devi implementare la logica per aggiornare il campo 'order' (se esiste)
        // Ad esempio, utilizzando un pacchetto come eloquent-sortable
    }
}
