<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\Widget;

class ProjectSlider extends Widget
{
    protected static ?string $heading = 'Ultimi Progetti';

    protected int|string|array $columnSpan = 'full';
    protected string $view = 'filament.widgets.project-slider';
    public function getProjects()
    {
        return Project::with('media')->latest()->get();
    }
}
