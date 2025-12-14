<?php

namespace App\Enums;

enum ProjectLocationStatus: string
{
    case PENDING = 'pending';
    case REQUESTED = 'requested';
    case CONFIRMED = 'confirmed';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'In attesa',
            self::REQUESTED => 'Richiesto',
            self::CONFIRMED => 'Confermato',
            self::COMPLETED => 'Completato',
            self::CANCELLED => 'Annullato',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING => 'gray',
            self::REQUESTED => 'info',
            self::CONFIRMED => 'success',
            self::COMPLETED => 'primary',
            self::CANCELLED => 'danger',
        };
    }

    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->getLabel();
        }
        return $options;
    }
}
