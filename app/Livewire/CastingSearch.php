<?php

namespace App\Livewire;

use App\Models\Profile;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Carbon;

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
        $query = Profile::query()
            ->with('user')
            ->when($this->search, function ($query) {
                $search = '%' . $this->search . '%';
                $query->where(function($q) use ($search) {
                    $q->where('stage_name', 'like', $search)
                      ->orWhereHas('user', function($q) use ($search) {
                          $q->where('name', 'like', $search);
                      });
                });
            })
            ->when($this->gender, function ($query) {
                $query->where('gender', $this->gender);
            })
            ->when($this->min_age || $this->max_age, function ($query) {
                $minDate = now()->subYears($this->max_age + 1)->format('Y-m-d');
                $maxDate = now()->subYears($this->min_age)->format('Y-m-d');
                $query->whereBetween('birth_date', [$minDate, $maxDate]);
            })
            ->when($this->eye_color, function ($query) {
                $query->whereJsonContains('appearance->eyes', $this->eye_color);
            })
            ->orderBy('created_at', 'desc')
            ->select('*')
            ->addSelect([
                // Add age calculation for display
                \DB::raw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) as age')
            ]);
        $profiles = $query->paginate(12);
        return view('livewire.casting-search', [
            'profiles' => $profiles,
        ]);
    }

    // Metodo per pulire tutti i filtri
    public function resetFilters()
    {
        $this->reset(['search', 'gender', 'min_age', 'max_age', 'eye_color']);
    }
}
