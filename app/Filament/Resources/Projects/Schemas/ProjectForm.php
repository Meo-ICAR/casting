<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

use Filament\Forms\Components\FileUpload;

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
                                ->helperText('Città dove si svolgono prevalentemente le riprese'),

FileUpload::make('poster_upload')
    ->label('Poster')
    ->image()
    ->imageEditor()
    ->directory('project-posters')
    ->visibility('public')
    ->preserveFilenames()
    ->imageResizeMode('cover')
    ->imageCropAspectRatio('2:3')
    ->imageResizeTargetWidth('800')
   // ->maxSize(2048)
     ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])

    ->downloadable()
    ->openable()
    ->imagePreviewHeight('400')
    ->columnSpan(2)
    ->afterStateUpdated(function ($state, $record) {
        if ($state) {
            $record->clearMediaCollection('poster');
            $record->addMedia($state)->toMediaCollection('poster');
        }
    }),
            ])
                    ]);
    }
}
