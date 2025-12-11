<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informazioni Base')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('service_type_id')
                                ->label('Tipo Servizio')
                                ->relationship('serviceType', 'name', fn ($query) => $query->where('is_active', true))
                                ->searchable()
                                ->preload()
                                ->required()
                                ->helperText('Gestisci i tipi di servizio nella tab \"Tipi di Servizio\"'),

                            TextInput::make('name')
                                ->label('Nome Azienda/Persona')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(2)
                                ->placeholder('Es: "Catering Roma" o "Mario Rossi - Parrucchiere"'),

                            TextInput::make('contact_name')
                                ->label('Nome Contatto')
                                ->maxLength(255)
                                ->placeholder('Nome del contatto principale'),

                            Select::make('user_id')
                                ->label('Collegato a Utente')
                                ->relationship('user', 'name', fn ($query) => $query->whereIn('role', ['director', 'admin']))
                                ->searchable()
                                ->preload()
                                ->helperText('Collega questo servizio a un utente del sistema (opzionale)'),

                            Toggle::make('is_active')
                                ->label('Attivo')
                                ->default(true)
                                ->helperText('Se disattivato, il servizio non sarà più visibile'),
                        ]),
                    ]),

                Section::make('Contatti')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->maxLength(255)
                                ->placeholder('email@example.com'),

                            TextInput::make('phone')
                                ->label('Telefono Fisso')
                                ->tel()
                                ->maxLength(20)
                                ->placeholder('+39 06 1234567'),

                            TextInput::make('mobile')
                                ->label('Cellulare')
                                ->tel()
                                ->maxLength(20)
                                ->placeholder('+39 333 1234567'),

                            TextInput::make('website')
                                ->label('Sito Web')
                                ->url()
                                ->maxLength(255)
                                ->placeholder('https://www.example.com')
                                ->prefix('https://'),
                        ]),
                    ]),

                Section::make('Indirizzo')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('address')
                                ->label('Indirizzo')
                                ->maxLength(255)
                                ->columnSpan(3)
                                ->placeholder('Via, numero civico'),

                            TextInput::make('city')
                                ->label('Città')
                                ->maxLength(255)
                                ->required(),

                            TextInput::make('province')
                                ->label('Provincia')
                                ->maxLength(2)
                                ->dehydrateStateUsing(fn ($state) => $state ? strtoupper($state) : null)
                                ->placeholder('RM'),

                            TextInput::make('postal_code')
                                ->label('CAP')
                                ->maxLength(10)
                                ->placeholder('00100'),

                            TextInput::make('country')
                                ->label('Nazione')
                                ->default('IT')
                                ->maxLength(2)
                                ->required(),
                        ]),
                    ]),

                Section::make('Informazioni Aggiuntive')
                    ->schema([
                        Textarea::make('description')
                            ->label('Descrizione')
                            ->rows(4)
                            ->columnSpanFull()
                            ->placeholder('Descrizione del servizio offerto, specializzazioni, esperienza...')
                            ->helperText('Informazioni pubbliche sul servizio'),

                        Textarea::make('notes')
                            ->label('Note Interne')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('Note private per il team di produzione')
                            ->helperText('Note visibili solo agli amministratori'),
                    ]),
            ]);
    }
}
