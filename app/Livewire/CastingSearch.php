<?php

namespace App\Livewire;

use App\Models\Profile;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class CastingSearch extends Component
{
    use WithPagination;

    // --- Proprietà pubbliche (stato dei filtri) ---
    public $search = ''; // Testo libero
    public $gender = '';
    public $min_age = 18;
    public $max_age = 80;
    public $eye_color = '';

    // Reset della paginazione quando si filtrano i risultati
    public function updatedSearch() { $this->resetPage(); }
    public function updatedGender() { $this->resetPage(); }
    public function updatedMinAge() { $this->resetPage(); }

    public function render()
    {
        // Logica di ricerca con Meilisearch
        $profiles = Profile::search($this->search, function ($meilisearch, $query, $options) {

            $filters = [];

            // Costruiamo la stringa di filtri per Meilisearch
            if (!empty($this->gender)) {
                $filters[] = 'gender = "' . $this->gender . '"';
            }

            if ($this->min_age || $this->max_age) {
                $filters[] = 'age >= ' . $this->min_age . ' AND age <= ' . $this->max_age;
            }

            if (!empty($this->eye_color)) {
                $filters[] = 'eye_color = "' . $this->eye_color . '"';
            }

            // Applichiamo i filtri se esistono
            if (!empty($filters)) {
                $options['filter'] = implode(' AND ', $filters);
            }

            return $meilisearch->search($query, $options);
        })->paginate(12);

        return view('livewire.casting-search', [
            'profiles' => $profiles
        ]);
    }

    // Metodo per pulire tutti i filtri
    public function resetFilters()
    {
        $this->reset(['search', 'gender', 'min_age', 'max_age', 'eye_color']);
    }
}
