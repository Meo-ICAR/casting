<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informazioni Base')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nome Azienda/Persona')
                            ->size('lg')
                            ->weight('bold')
                            ->icon('heroicon-o-building-office'),

                        TextEntry::make('service_type')
                            ->label('Tipo Servizio')
                            ->badge()
                            ->formatStateUsing(fn ($state) => match($state) {
                                'catering' => 'Catering',
                                'hair' => 'Parrucchiere',
                                'makeup' => 'Truccatrice',
                                'costume' => 'Sartoria/Costumi',
                                'location' => 'Location',
                                'equipment' => 'Attrezzature',
                                'transport' => 'Trasporti',
                                'security' => 'Sicurezza',
                                'photography' => 'Fotografia',
                                'video' => 'Video',
                                'sound' => 'Audio',
                                'other' => 'Altro',
                                default => ucfirst($state),
                            })
                            ->color(fn ($state) => match($state) {
                                'catering' => 'success',
                                'hair' => 'info',
                                'makeup' => 'warning',
                                'costume' => 'danger',
                                default => 'gray',
                            }),

                        TextEntry::make('contact_name')
                            ->label('Nome Contatto')
                            ->placeholder('Non specificato')
                            ->icon('heroicon-o-user'),

                        IconEntry::make('is_active')
                            ->label('Stato')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger')
                            ->formatStateUsing(fn ($state) => $state ? 'Attivo' : 'Non Attivo'),

                        TextEntry::make('created_at')
                            ->label('Creato il')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-o-clock'),

                        TextEntry::make('updated_at')
                            ->label('Ultimo Aggiornamento')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-o-clock'),
                    ])
                    ->columns(2),

                Section::make('Contatti')
                    ->schema([
                        TextEntry::make('email')
                            ->label('Email')
                            ->placeholder('Non specificata')
                            ->icon('heroicon-o-envelope')
                            ->copyable(),

                        TextEntry::make('phone')
                            ->label('Telefono Fisso')
                            ->placeholder('Non specificato')
                            ->icon('heroicon-o-phone')
                            ->copyable(),

                        TextEntry::make('mobile')
                            ->label('Cellulare')
                            ->placeholder('Non specificato')
                            ->icon('heroicon-o-device-phone-mobile')
                            ->copyable(),

                        TextEntry::make('website')
                            ->label('Sito Web')
                            ->placeholder('Non specificato')
                            ->icon('heroicon-o-globe-alt')
                            ->url(fn ($record) => $record->website)
                            ->openUrlInNewTab(),
                    ])
                    ->columns(2),

                Section::make('Indirizzo')
                    ->schema([
                        TextEntry::make('address')
                            ->label('Indirizzo')
                            ->placeholder('Non specificato')
                            ->icon('heroicon-o-map-pin')
                            ->columnSpanFull(),

                        TextEntry::make('city')
                            ->label('Città')
                            ->placeholder('Non specificata'),

                        TextEntry::make('province')
                            ->label('Provincia')
                            ->placeholder('Non specificata'),

                        TextEntry::make('postal_code')
                            ->label('CAP')
                            ->placeholder('Non specificato'),

                        TextEntry::make('country')
                            ->label('Nazione')
                            ->placeholder('Non specificata'),
                    ])
                    ->columns(2),

                Section::make('Informazioni')
                    ->schema([
                        TextEntry::make('description')
                            ->label('Descrizione')
                            ->placeholder('Nessuna descrizione disponibile')
                            ->columnSpanFull()
                            ->markdown(),

                        TextEntry::make('notes')
                            ->label('Note Interne')
                            ->placeholder('Nessuna nota')
                            ->columnSpanFull()
                            ->markdown(),
                    ]),
            ]);
    }
}
