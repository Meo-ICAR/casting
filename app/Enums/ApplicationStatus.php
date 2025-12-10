<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case PENDING = 'pending';
    case UNDER_REVIEW = 'under_review';
    case CALLBACK = 'callback';
    case REJECTED = 'rejected';
    case ACCEPTED = 'accepted';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'In attesa',
            self::UNDER_REVIEW => 'In valutazione',
            self::CALLBACK => 'Richiamato/a',
            self::REJECTED => 'Scartato/a',
            self::ACCEPTED => 'Accettato/a',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
