<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informazioni Base')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('Titolo Progetto')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(2)
                                ->placeholder('Es: "Il Grande Film"'),

                            Select::make('user_id')
                                ->label('Casting Director')
                                ->relationship('owner', 'name', fn ($query) => $query->whereIn('role', ['director', 'admin']))
                                ->searchable()
                                ->preload()
                    ->required()
                                ->default(fn () => auth()->id())
                                ->helperText('Il direttore di casting responsabile del progetto'),

                            Select::make('type')
                                ->label('Tipo Progetto')
                                ->options([
                                    'feature_film' => 'Film Lungometraggio',
                                    'commercial' => 'Spot Pubblicitario',
                                    'tv_series' => 'Serie TV',
                                    'short' => 'Cortometraggio',
                                    'documentary' => 'Documentario',
                                    'web_series' => 'Web Series',
                                ])
                    ->required()
                                ->default('feature_film')
                                ->native(false),

                            Select::make('status')
                                ->label('Stato Progetto')
                                ->options([
                                    'casting' => 'In Casting',
                                    'production' => 'In Produzione',
                                    'wrapped' => 'Completato',
                                    'cancelled' => 'Annullato',
                                ])
                    ->required()
                                ->default('casting')
                                ->native(false),

                            TextInput::make('production_company')
                                ->label('Casa di Produzione')
                                ->maxLength(255)
                                ->placeholder('Es: "Universal Pictures Italia"'),

                            DatePicker::make('start_date')
                                ->label('Data Inizio Produzione')
                                ->native(false)
                                ->displayFormat('d/m/Y')
                                ->helperText('Data prevista di inizio delle riprese'),
                        ]),
                    ]),

                Section::make('Descrizione')
                    ->schema([
                        Textarea::make('description')
                            ->label('Descrizione Progetto')
                            ->rows(6)
                            ->columnSpanFull()
                            ->placeholder('Descrizione dettagliata del progetto, sinossi, note per gli attori...')
                            ->helperText('Informazioni che verranno mostrate agli attori interessati'),
                    TextInput::make('city')
                                ->label('Città')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Es: Milano, Roma, Napoli')
                                ->helperText('Città dove si svolgono prevalentemente le riprese')

                    ])
            ]);
    }
}
