<?php

namespace App\Filament\Resources\Locations\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Forms\Get;
use Filament\Forms\Set;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dettagli Location')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nome Location')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Es. Villa storica, Studio 1, Capannone industriale'),
                                TextInput::make('city')
                                    ->label('Città')
                                    ->required()
                                    ->maxLength(100),
                                TextInput::make('province')
                                    ->label('Provincia')
                                    ->maxLength(2),
                                TextInput::make('postal_code')
                                    ->label('CAP')
                                    ->maxLength(10),
                                Select::make('country')
                                    ->label('Nazione')
                                    ->options([
                                        'IT' => 'Italia',
                                        'US' => 'Stati Uniti',
                                        'GB' => 'Regno Unito',
                                        'DE' => 'Germania',
                                        'FR' => 'Francia',
                                        'ES' => 'Spagna',
                                    ])
                                    ->default('IT')
                                    ->required(),
                                Toggle::make('is_active')
                                    ->label('Attiva')
                                    ->default(true)
                                    ->required(),
                            ]),
                        TextInput::make('address')
                            ->label('Indirizzo')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->label('Descrizione')
                            ->placeholder('Descrizione della location, spazi disponibili, logistica, accessi...')
                            ->columnSpanFull(),
                    ]),

                Section::make('Foto Location')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('photos')
                            ->label('Foto della Location')
                            ->collection('photos')
                            ->image()
                            ->imageEditor()
                            ->multiple()
                            ->reorderable()
                            ->maxFiles(20)
                            ->maxSize(10240) // 10MB
                            ->columnSpanFull(),
                    ]),

                Section::make('Contatti')
                    ->schema([
                        TextInput::make('contact_person')
                            ->label('Referente')
                            ->maxLength(255),
                        TextInput::make('contact_phone')
                            ->label('Telefono')
                            ->tel()
                            ->maxLength(50),
                        TextInput::make('contact_email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),
                    ]),

                Section::make('Mappa')
                    ->description('Aggiungi coordinate per mostrare la location sulla mappa')
                    ->schema([
                        TextInput::make('latitude')
                            ->label('Latitudine')
                            ->numeric()
                            ->reactive()
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                if (is_numeric($state) && ($state < -90 || $state > 90)) {
                                    $set('latitude', min(90, max(-90, (float) $state)));
                                }
                            }),
                        TextInput::make('longitude')
                            ->label('Longitudine')
                            ->numeric()
                            ->reactive()
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                if (is_numeric($state) && ($state < -180 || $state > 180)) {
                                    $set('longitude', min(180, max(-180, (float) $state)));
                                }
                            }),
                    ]),

                Section::make('Informazioni Aggiuntive')
                    ->schema([
                        TagsInput::make('features')
                            ->label('Caratteristiche (tag)')
                            ->placeholder('Parcheggio, Interni, Esterni, Ufficio produzione...')
                            ->separator(',')
                            ->suggestions(['Parcheggio', 'Interni', 'Esterni', 'Spogliatoi', 'Magazzino', 'Ufficio produzione'])
                            ->columnSpanFull(),
                        Textarea::make('notes')
                            ->label('Note interne')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
