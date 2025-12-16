<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Colors\ColorManager;

enum ProjectType: string
{
    case FEATURE_FILM = 'feature_film';
    case COMMERCIAL = 'commercial';
    case TV_SERIES = 'tv_series';
    case SHORT = 'short';
    case DOCUMENTARY = 'documentary';
    case WEB_SERIES = 'web_series';

    public function label(): string
    {
        return match($this) {
            self::FEATURE_FILM => 'Film',
            self::COMMERCIAL => 'Spot',
            self::TV_SERIES => 'Serie TV',
            self::SHORT => 'Corto',
            self::DOCUMENTARY => 'Doc',
            self::WEB_SERIES => 'Web',
        };
    }

    public function filterLabel(): string
    {
        return match($this) {
            self::FEATURE_FILM => 'Film Lungometraggio',
            self::COMMERCIAL => 'Spot Pubblicitario',
            self::TV_SERIES => 'Serie TV',
            self::SHORT => 'Cortometraggio',
            self::DOCUMENTARY => 'Documentario',
            self::WEB_SERIES => 'Web Series',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::FEATURE_FILM => 'success',
            self::TV_SERIES => 'info',
            self::COMMERCIAL => 'warning',
            self::SHORT => 'primary',
            self::DOCUMENTARY => 'indigo',
            self::WEB_SERIES => 'purple',
            default => 'gray',
        };
    }

    public static function options(): array
    {
        return array_combine(
            array_column(self::cases(), 'value'),
            array_map(fn($case) => $case->label(), self::cases())
        );
    }
}
