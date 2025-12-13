<?php

namespace App\Filament\Resources\ProjectLocations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProjectLocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
            Forms\Components\Select::make('project_id')
                ->label('Progetto')
                ->relationship('project', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->label('Nome'),
                ]),

            Forms\Components\Section::make('Dettagli Location')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nome')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Select::make('location_type')
                        ->label('Tipologia')
                        ->options(ProjectLocation::getLocationTypeOptions())
                        ->required()
                        ->native(false),

                    Forms\Components\Textarea::make('description')
                        ->label('Descrizione')
                        ->maxLength(65535)
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Indirizzo')
                ->schema([
                    Forms\Components\TextInput::make('address')
                        ->label('Indirizzo')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('city')
                        ->label('Città')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('province')
                        ->label('Provincia')
                        ->maxLength(2)
                        ->hint('Es. MI, RM, TO'),

                    Forms\Components\TextInput::make('postal_code')
                        ->label('CAP')
                        ->maxLength(10),

                    Forms\Components\TextInput::make('country')
                        ->label('Nazione')
                        ->default('IT')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('latitude')
                        ->label('Latitudine')
                        ->numeric()
                        ->step('0.000001'),

                    Forms\Components\TextInput::make('longitude')
                        ->label('Longitudine')
                        ->numeric()
                        ->step('0.000001'),
                ])->columns(2),

            Forms\Components\Section::make('Programmazione Riprese')
                ->schema([
                    Forms\Components\DatePicker::make('shooting_date')
                        ->label('Data riprese'),

                    Forms\Components\TimePicker::make('shooting_time_from')
                        ->label('Ora inizio')
                        ->seconds(false),

                    Forms\Components\TimePicker::make('shooting_time_to')
                        ->label('Ora fine')
                        ->seconds(false),
                ])->columns(3),

            Forms\Components\Section::make('Informazioni Aggiuntive')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('Stato')
                        ->options(ProjectLocation::getStatusOptions())
                        ->default(ProjectLocation::STATUS_PENDING)
                        ->required()
                        ->native(false),
                        Forms\Toggle::make('is_open')
    ->label('Aperto')
    ->default(true)
    ->columnSpan(1),

                    Forms\Components\Toggle::make('permission_required')
                        ->label('Richiesta autorizzazione')
                        ->required(),

                    Forms\Components\Textarea::make('permission_details')
                        ->label('Dettagli autorizzazione')
                        ->maxLength(65535)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('notes')
                        ->label('Note')
                        ->maxLength(65535)
                        ->columnSpanFull(),

                    Forms\Components\KeyValue::make('specifications')
                        ->label('Specifiche aggiuntive')
                        ->keyLabel('Nome proprietà')
                        ->valueLabel('Valore')
                        ->reorderable()
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
