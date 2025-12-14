<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasLabel, HasColor
{
    case ADMIN = 'admin';
    case DIRECTOR = 'director';
    case ACTOR = 'actor';
    case HOST = 'host';
    case SERVICER = 'servicer';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return [
            self::ADMIN->value => 'Amministratore',
            self::DIRECTOR->value => 'Regista',
            self::ACTOR->value => 'Attore',
            self::HOST->value => 'Ospite',
            self::SERVICER->value => 'Fornitore Servizi',
        ];
    }

    public function getLabel(): ?string
    {
        return match($this) {
            self::ADMIN => 'Amministratore',
            self::DIRECTOR => 'Regista',
            self::ACTOR => 'Attore',
            self::HOST => 'Ospite',
            self::SERVICER => 'Fornitore Servizi',
        };
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            self::ADMIN => 'danger',
            self::DIRECTOR => 'primary',
            self::ACTOR => 'success',
            self::HOST => 'warning',
            self::SERVICER => 'info',
        };
    }

    public function getIcon(): ?string
    {
        return match($this) {
            self::ADMIN => 'heroicon-o-shield-check',
            self::DIRECTOR => 'heroicon-o-film',
            self::ACTOR => 'heroicon-o-user-circle',
            self::HOST => 'heroicon-o-home',
            self::SERVICER => 'heroicon-o-wrench-screwdriver',
        };
    }
}
