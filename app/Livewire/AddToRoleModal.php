<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Role;
use App\Models\Application;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AddToRoleModal extends Component
{
    public $profileId;
    public $showModal = false; // Controlla visibilità

    public $selectedProjectId = null;
    public $selectedRoleId = null;

    protected $listeners = ['openAddToRoleModal' => 'open'];

    public function open($profileId)
    {
        $this->profileId = $profileId;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'selectedRoleId' => 'required|exists:roles,id',
        ]);

        // Crea la candidatura
        Application::create([
            'role_id' => $this->selectedRoleId,
            'profile_id' => $this->profileId,
            'status' => 'invited', // Stato iniziale
            'director_notes' => 'Aggiunto manualmente dalla ricerca.',
        ]);

        $this->showModal = false;
        $this->reset(['selectedProjectId', 'selectedRoleId']);

        // Feedback utente (es. con Filament notifications o session flash)
        session()->flash('message', 'Attore invitato al ruolo con successo!');
    }

    public function render()
    {
        // Carica solo i progetti dell'utente loggato che sono aperti
        $projects = Auth::check()
            ? Project::where('user_id', Auth::id())->where('status', 'casting')->get()
            : [];

        // Carica i ruoli solo se è stato selezionato un progetto
        $roles = $this->selectedProjectId
            ? Role::where('project_id', $this->selectedProjectId)->get()
            : [];

        return view('livewire.add-to-role-modal', [
            'projects' => $projects,
            'roles' => $roles
        ]);
    }
}
