<?php

namespace App\Enums;

enum ProjectLocationType: string
{
    case INTERNAL = 'internal';
    case EXTERNAL = 'external';
    case PUBLIC = 'public';
    case PRIVATE = 'private';
    case OTHER = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::INTERNAL => 'Interna',
            self::EXTERNAL => 'Esterna',
            self::PUBLIC => 'Pubblica',
            self::PRIVATE => 'Privata',
            self::OTHER => 'Altro',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::INTERNAL => 'info',
            self::EXTERNAL => 'primary',
            self::PUBLIC => 'success',
            self::PRIVATE => 'warning',
            self::OTHER => 'gray',
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
