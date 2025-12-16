<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ProjectSlider;
use Filament\Pages\Dashboard as BaseDashboard;
use BackedEnum;
use UnitEnum;

class Dashboard extends BaseDashboard
{


    protected string $view = 'filament.pages.dashboard';

      protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-home';


    protected function getHeaderWidgets(): array
    {
        return [
            ProjectSlider::class,
        ];
    }

    public function getColumns(): int | array
    {
        return 1; // Single column layout
    }
}
