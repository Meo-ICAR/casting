<?php

namespace App\Livewire;

use App\Models\Profile;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ShortlistButton extends Component
{
    public $profileId;
    public $isShortlisted = false;

    public function mount($profileId)
    {
        $this->profileId = $profileId;

        // Se l'utente è loggato, controlliamo se l'attore è già nella lista
        if (Auth::check()) {
            $this->isShortlisted = Auth::user()->shortlistedProfiles()
                ->where('profile_id', $profileId)
                ->exists();
        }
    }

    public function toggle()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Il metodo 'toggle' aggiunge se non c'è, rimuove se c'è. Magico.
        Auth::user()->shortlistedProfiles()->toggle($this->profileId);

        // Aggiorniamo lo stato locale per cambiare il colore del bottone
        $this->isShortlisted = ! $this->isShortlisted;

        // Opzionale: Invia un evento per mostrare una notifica Toast
        $this->dispatch('notify', message: $this->isShortlisted ? 'Aggiunto alla Shortlist' : 'Rimosso dalla Shortlist');
    }

    public function render()
    {
        return view('livewire.shortlist-button');
    }
}
