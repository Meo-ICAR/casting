<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;


enum ApplicationStatus: string implements HasLabel
{
    case DISPONIBILITA = 'disponibilita';
    case PENDING = 'pending';
    case UNDER_REVIEW = 'under_review';
    case CALLBACK = 'callback';
    case REJECTED = 'rejected';
    case ACCEPTED = 'accepted';

      public function getLabel(): string
    {
        return match($this) {
            self::DISPONIBILITA => 'Disponibilità',
            self::PENDING => 'In attesa',
            self::UNDER_REVIEW => 'In valutazione',
            self::CALLBACK => 'Richiamato/a',
            self::REJECTED => 'Scartato/a',
            self::ACCEPTED => 'Accettato/a',
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
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
