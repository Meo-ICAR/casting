<?php

namespace App\Filament\Resources\ServiceTypes\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceTypeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tipo di Servizio')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nome')
                            ->size('lg')
                            ->weight('bold'),

                        TextEntry::make('slug')
                            ->label('Slug'),

                        IconEntry::make('is_active')
                            ->label('Stato')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger')
                            ->formatStateUsing(fn ($state) => $state ? 'Attivo' : 'Non Attivo'),

                        TextEntry::make('description')
                            ->label('Descrizione')
                            ->placeholder('Nessuna descrizione')
                            ->columnSpanFull()
                            ->markdown(),

                        TextEntry::make('created_at')
                            ->label('Creato il')
                            ->dateTime('d/m/Y H:i'),

                        TextEntry::make('updated_at')
                            ->label('Ultimo aggiornamento')
                            ->dateTime('d/m/Y H:i'),
                    ])
                    ->columns(2),
            ]);
    }
}
