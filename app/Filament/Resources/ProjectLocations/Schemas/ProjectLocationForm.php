<?php

namespace App\Filament\Resources\ProjectLocations\Schemas;

use App\Models\ProjectLocation;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\KeyValue;
use Filament\Schemas\Components\Section;
use Filament\Tables\Enums\ActionsPosition;


class ProjectLocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
           Select::make('project_id')
                ->label('Progetto')
                ->relationship('project', 'title')
                ->searchable()
                ->preload()
                ->required()
                ->createOptionForm([
                   TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->label('Progetto'),
                ]),

          Section::make('Dettagli Location')
                ->schema([
                    TextInput::make('name')
                        ->label('Nome')
                        ->required()
                        ->maxLength(255),

                    Select::make('location_type')
                        ->label('Tipologia')
                        ->options(ProjectLocation::getLocationTypeOptions())
                        ->required()
                        ->native(false),

                    Textarea::make('description')
                        ->label('Descrizione')
                        ->maxLength(65535)
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('Indirizzo')
                ->schema([
                   TextInput::make('address')
                        ->label('Indirizzo')
                        ->maxLength(255),

                    TextInput::make('city')
                        ->label('Città')
                        ->required()
                        ->maxLength(255),

                   TextInput::make('province')
                        ->label('Provincia')
                        ->maxLength(2)
                        ->hint('Es. MI, RM, TO'),

                  TextInput::make('postal_code')
                        ->label('CAP')
                        ->maxLength(10),

                    TextInput::make('country')
                        ->label('Nazione')
                        ->default('IT')
                        ->maxLength(255),

                    TextInput::make('latitude')
                        ->label('Latitudine')
                        ->numeric()
                        ->step('0.000001'),

                    TextInput::make('longitude')
                        ->label('Longitudine')
                        ->numeric()
                        ->step('0.000001'),
                ])->columns(2),

            Section::make('Programmazione Riprese')
                ->schema([
                   DatePicker::make('shooting_date')
                        ->label('Data riprese'),

                   TimePicker::make('shooting_time_from')
                        ->label('Ora inizio')
                        ->seconds(false),

                   TimePicker::make('shooting_time_to')
                        ->label('Ora fine')
                        ->seconds(false),
                ])->columns(3),

            Section::make('Informazioni Aggiuntive')
                ->schema([
                    Select::make('status')
                        ->label('Stato')
                        ->options(ProjectLocation::getStatusOptions())
                        ->default(ProjectLocation::STATUS_PENDING)
                        ->required()
                        ->native(false),
                Toggle::make('is_open')
    ->label('Aperto')
    ->default(true)
    ->columnSpan(1),

                    Toggle::make('permission_required')
                        ->label('Richiesta autorizzazione')
                        ->required(),

                    Textarea::make('permission_details')
                        ->label('Dettagli autorizzazione')
                        ->maxLength(65535)
                        ->columnSpanFull(),

                   Textarea::make('notes')
                        ->label('Note')
                        ->maxLength(65535)
                        ->columnSpanFull(),

                   KeyValue::make('specifications')
                        ->label('Specifiche aggiuntive')
                        ->keyLabel('Nome proprietà')
                        ->valueLabel('Valore')
                        ->reorderable()
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
