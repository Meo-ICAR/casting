<?php

namespace App\Enums;

enum UserRole: string
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
            self::ADMIN->value => 'Admin',
            self::DIRECTOR->value => 'Director',
            self::ACTOR->value => 'Actor',
            self::HOST->value => 'Host',
            self::SERVICER->value => 'Servicer',
        ];
    }
}
