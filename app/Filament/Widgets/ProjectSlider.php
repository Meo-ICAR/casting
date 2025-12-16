<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Filament\Resources\Projects\Tables\ProjectsTableView;

class ProjectSlider extends BaseWidget
{
    protected static ?string $heading = 'Ultimi Ruoli';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return ProjectsTableView::configure($table);
    }

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return Project::query()
            ->with(['roles'])
            ->latest();
    }
}
