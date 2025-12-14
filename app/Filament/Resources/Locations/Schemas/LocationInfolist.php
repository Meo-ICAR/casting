<?php

namespace App\Filament\Resources\Locations\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LocationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dettagli Location')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nome')
                            ->size('lg')
                            ->weight('bold')
                            ->icon('heroicon-o-building-office'),

                        TextEntry::make('city')
                            ->label('Città')
                            ->placeholder('Non specificata'),

                        TextEntry::make('province')
                            ->label('Provincia')
                            ->placeholder('Non specificata'),

                        TextEntry::make('country')
                            ->label('Nazione')
                            ->placeholder('Non specificata'),

                        IconEntry::make('is_active')
                            ->label('Stato')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger')
                            ->formatStateUsing(fn ($state) => $state ? 'Attiva' : 'Non attiva'),
                    ])
                    ->columns(2),

                Section::make('Foto')
                    ->schema([
                        ImageEntry::make('photos')
                            ->label('Galleria')
                            ->getStateUsing(fn ($record) => $record->getMedia('photos'))
                            ->columnSpanFull(),
                    ]),

                Section::make('Contatti')
                    ->schema([
                        TextEntry::make('contact_person')
                            ->label('Referente')
                            ->placeholder('Non specificato')
                            ->icon('heroicon-o-user'),

                        TextEntry::make('contact_phone')
                            ->label('Telefono')
                            ->placeholder('Non specificato')
                            ->icon('heroicon-o-phone')
                            ->copyable(),

                        TextEntry::make('contact_email')
                            ->label('Email')
                            ->placeholder('Non specificata')
                            ->icon('heroicon-o-envelope')
                            ->copyable()
                            ->url(fn ($record) => $record->contact_email ? 'mailto:' . $record->contact_email : null),
                    ])
                    ->columns(2),

                Section::make('Indirizzo')
                    ->schema([
                        TextEntry::make('address')
                            ->label('Indirizzo')
                            ->placeholder('Non specificato')
                            ->columnSpanFull(),

                        TextEntry::make('postal_code')
                            ->label('CAP')
                            ->placeholder('Non specificato'),

                        TextEntry::make('latitude')
                            ->label('Latitudine')
                            ->placeholder('Non impostata'),

                        TextEntry::make('longitude')
                            ->label('Longitudine')
                            ->placeholder('Non impostata'),
                    ])
                    ->columns(2),

                Section::make('Info aggiuntive')
                    ->schema([
                        TextEntry::make('description')
                            ->label('Descrizione')
                            ->placeholder('Nessuna descrizione disponibile')
                            ->columnSpanFull()
                            ->markdown(),

                        TextEntry::make('features')
                            ->label('Caratteristiche')
                            ->badge()
                            ->separator(',')
                            ->placeholder('Nessuna caratteristica indicata')
                            ->columnSpanFull(),

                        TextEntry::make('notes')
                            ->label('Note interne')
                            ->placeholder('Nessuna nota')
                            ->columnSpanFull()
                            ->markdown(),
                    ]),
            ]);
    }
}




