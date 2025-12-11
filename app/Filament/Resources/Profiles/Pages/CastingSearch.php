<?php

namespace App\Filament\Resources\Profiles\Pages;

use App\Filament\Resources\Profiles\ProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\Page;
use Livewire\WithPagination;
use App\Models\Profile;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;

class CastingSearch extends Page
{
    use WithPagination;

    protected static string $resource = ProfileResource::class;
    protected static string $view = 'filament.resources.profiles.pages.casting-search';

    public $search = '';
    public $gender = '';
    public $min_age = 18;
    public $max_age = 80;
    public $eye_color = '';

    public function mount(): void
    {
        parent::boot();
    }

    public function getViewData(): array
    {
        $query = Profile::search($this->search, function ($meilisearch, $query, $options) {
            $filters = [];

            if (!empty($this->gender)) {
                $filters[] = 'gender = "' . $this->gender . '"';
            }

            if ($this->min_age || $this->max_age) {
                $filters[] = 'age >= ' . $this->min_age . ' AND age <= ' . $this->max_age;
            }

            if (!empty($this->eye_color)) {
                $filters[] = 'eye_color = "' . $this->eye_color . '"';
            }

            if (!empty($filters)) {
                $options['filter'] = implode(' AND ', $filters);
            }

            return $meilisearch->search($query, $options);
        });

        return [
            'profiles' => $query->paginate(12),
        ];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedGender()
    {
        $this->resetPage();
    }

    public function updatedMinAge()
    {
        $this->resetPage();
    }

    public function updatedMaxAge()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'gender', 'min_age', 'max_age', 'eye_color']);
    }

    public function getBreadcrumb(): ?string
    {
        return 'Casting Search';
    }
}
