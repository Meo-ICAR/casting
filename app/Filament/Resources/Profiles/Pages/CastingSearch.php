<?php

namespace App\Filament\Resources\Profiles\Pages;

use App\Filament\Resources\Profiles\ProfileResource;
use App\Models\Profile;
use Filament\Resources\Pages\Page;
use Livewire\WithPagination;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CastingSearch extends Page
{
    use WithPagination;

    protected static string $resource = ProfileResource::class;
    protected string $view = 'filament.resources.profiles.pages.casting-search';
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-magnifying-glass-circle';
    protected static ?string $navigationLabel = 'Casting Search';
    protected static ?string $title = 'Casting Search';
    protected static UnitEnum|string|null $navigationGroup = 'Produzione';
    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return true;
    }

    public $search = '';
    public $gender = '';
    public $min_age = null;
    public $max_age = null;
    public $eye_color = '';

    protected function getViewData(): array
    {
        $query = Profile::search($this->search, function ($meilisearch, $query, $options) {
            $filters = [];

            if (!empty($this->gender)) {
                $filters[] = 'gender = "' . $this->gender . '"';
            }

            if ($this->min_age !== null && $this->min_age !== '') {
                $filters[] = 'age >= ' . (int) $this->min_age;
            }

            if ($this->max_age !== null && $this->max_age !== '') {
                $filters[] = 'age <= ' . (int) $this->max_age;
            }

            if (!empty($this->eye_color)) {
                $filters[] = 'eye_color = "' . $this->eye_color . '"';
            }

            $filters[] = 'is_visible = true';

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
