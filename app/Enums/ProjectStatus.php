<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case CASTING = 'casting';
    case PRODUCTION = 'production';
    case WRAPPED = 'wrapped';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::CASTING => 'In Casting',
            self::PRODUCTION => 'In Produzione',
            self::WRAPPED => 'Completato',
            self::CANCELLED => 'Annullato',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::CASTING => 'info',
            self::PRODUCTION => 'warning',
            self::WRAPPED => 'success',
            self::CANCELLED => 'danger',
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
