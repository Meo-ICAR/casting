<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RoleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informazioni Ruolo')
                    ->schema([
                TextEntry::make('project.title')
                            ->label('Progetto')
                            ->icon('heroicon-o-film'),

                        TextEntry::make('name')
                            ->label('Nome Ruolo')
                            ->size('lg')
                            ->weight('bold')
                            ->icon('heroicon-o-user-group'),
                        TextEntry::make('city')
                            ->label('Città')
                            ->size('lg')
                            ->weight('medium')
                            ->icon('heroicon-o-map-pin')
                            ->columnSpanFull()
                            ->formatStateUsing(fn ($state) => $state ?: 'Non specificata')
                            ->color('primary'),

                IconEntry::make('is_open')
                            ->label('Stato')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger')
                            ->formatStateUsing(fn ($state) => $state ? 'Aperto alle candidature' : 'Chiuso alle candidature'),

                        TextEntry::make('applications_count')
                            ->label('Candidature Ricevute')
                            ->counts('applications')
                            ->badge()
                            ->color('gray')
                            ->icon('heroicon-o-envelope'),



                    ])
                    ->columns(2),

                Section::make('Descrizione')
                    ->schema([
                        TextEntry::make('description')
                            ->label('Descrizione Ruolo')
                            ->placeholder('Nessuna descrizione disponibile')
                            ->columnSpanFull()
                            ->markdown(),
                    ]),

                Section::make('Requisiti')
                    ->schema([
                        TextEntry::make('requirements.gender')
                            ->label('Genere')
                            ->formatStateUsing(fn ($state) => match($state) {
                                'male' => 'Uomo',
                                'female' => 'Donna',
                                'non_binary' => 'Non-Binary',
                                'any' => 'Qualsiasi',
                                default => $state ?? 'Non specificato',
                            })
                            ->placeholder('Non specificato'),

                        TextEntry::make('age_range')
                            ->label('Range Età')
                            ->getStateUsing(function ($record) {
                                $min = $record->requirements['age_min'] ?? null;
                                $max = $record->requirements['age_max'] ?? null;
                                if ($min && $max) {
                                    return "{$min} - {$max} anni";
                                } elseif ($min) {
                                    return "Da {$min} anni";
                                } elseif ($max) {
                                    return "Fino a {$max} anni";
                                }
                                return 'Non specificato';
                            })
                            ->placeholder('Non specificato'),

                        TextEntry::make('height_range')
                            ->label('Range Altezza')
                            ->getStateUsing(function ($record) {
                                $min = $record->requirements['height_min'] ?? null;
                                $max = $record->requirements['height_max'] ?? null;
                                if ($min && $max) {
                                    return "{$min} - {$max} cm";
                                } elseif ($min) {
                                    return "Da {$min} cm";
                                } elseif ($max) {
                                    return "Fino a {$max} cm";
                                }
                                return 'Non specificato';
                            })
                            ->placeholder('Non specificato'),

                        TextEntry::make('requirements.skills')
                            ->label('Skills Richieste')
                            ->badge()
                            ->separator(',')
                            ->placeholder('Nessuna skill specifica')
                            ->columnSpanFull(),

                        TextEntry::make('requirements.languages')
                            ->label('Lingue Richieste')
                            ->badge()
                            ->separator(',')
                            ->placeholder('Nessuna lingua specifica')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
