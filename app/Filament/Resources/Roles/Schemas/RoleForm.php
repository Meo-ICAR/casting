<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TagsInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informazioni Base')
                    ->schema([
                        Grid::make(2)->schema([
                Select::make('project_id')
                                ->label('Progetto')
                    ->relationship('project', 'title')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->helperText('Seleziona il progetto a cui appartiene questo ruolo'),

                TextInput::make('name')
                                ->label('Nome Ruolo')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Es: Protagonista, Antagonista, Compagno/a')
                                ->helperText('Nome del ruolo nel progetto'),
                        ]),

                        Textarea::make('description')
                            ->label('Descrizione Ruolo')
                            ->rows(4)
                            ->columnSpanFull()
                            ->placeholder('Descrizione dettagliata del ruolo, caratteristiche del personaggio, requisiti...')
                            ->helperText('Informazioni che verranno mostrate agli attori interessati'),

                        Toggle::make('is_open')
                            ->label('Accetta Candidature')
                            ->default(true)
                            ->helperText('Se disattivato, il ruolo non accetterà nuove candidature')
                    ->required(),
                    ]),

                Section::make('Requisiti')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('requirements.gender')
                                ->label('Genere Richiesto')
                                ->options([
                                    'male' => 'Uomo',
                                    'female' => 'Donna',
                                    'non_binary' => 'Non-Binary',
                                    'any' => 'Qualsiasi',
                                ])
                                ->native(false)
                                ->placeholder('Non specificato'),

                            TextInput::make('requirements.age_min')
                                ->label('Età Minima')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->placeholder('Es: 18'),

                            TextInput::make('requirements.age_max')
                                ->label('Età Massima')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->placeholder('Es: 35'),

                            TextInput::make('requirements.height_min')
                                ->label('Altezza Minima (cm)')
                                ->numeric()
                                ->minValue(50)
                                ->maxValue(250)
                                ->placeholder('Es: 160'),

                            TextInput::make('requirements.height_max')
                                ->label('Altezza Massima (cm)')
                                ->numeric()
                                ->minValue(50)
                                ->maxValue(250)
                                ->placeholder('Es: 190'),
                        ]),

                        TagsInput::make('requirements.skills')
                            ->label('Skills Richieste')
                            ->placeholder('Aggiungi skill (es: Equitazione, Scherma, Canto)')
                            ->separator(',')
                            ->columnSpanFull()
                            ->helperText('Skills specifiche richieste per questo ruolo'),

                        TagsInput::make('requirements.languages')
                            ->label('Lingue Richieste')
                            ->placeholder('Aggiungi lingue (es: Inglese, Francese)')
                            ->separator(',')
                            ->columnSpanFull()
                            ->helperText('Lingue che l\'attore deve parlare'),
                    ]),

                Section::make('Compenso')
                    ->schema([
                        Grid::make(2)->schema([
                TextInput::make('salary_min')
                                ->label('Compenso Minimo (€)')
                                ->numeric()
                                ->minValue(0)
                                ->prefix('€')
                                ->placeholder('Es: 1000')
                                ->helperText('Compenso minimo previsto'),

                TextInput::make('salary_max')
                                ->label('Compenso Massimo (€)')
                                ->numeric()
                                ->minValue(0)
                                ->prefix('€')
                                ->placeholder('Es: 5000')
                                ->helperText('Compenso massimo previsto'),
                        ]),
                    ]),
            ]);
    }
}
