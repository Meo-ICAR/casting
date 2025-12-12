<?php

namespace App\Filament\Resources\ProjectServices\Schemas;

use App\Models\ProjectService;
use Filament\Forms\Components as Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\KeyValue;


class ProjectServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informazioni Base')
                    ->schema([
                        Forms\Select::make('project_id')
                            ->label('Progetto')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Select::make('service_type_id')
                            ->label('Tipo di servizio')
                            ->relationship('serviceType', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\TextInput::make('name')
                                    ->label('Nome')
                                    ->required()
                                    ->maxLength(255),
                                Forms\TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        Forms\TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),

                        Forms\Textarea::make('description')
                            ->label('Descrizione')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),

                Section::make('Dettagli')
                    ->schema([
                        Forms\TextInput::make('city')
                            ->label('Città')
                            ->maxLength(255),

                        Forms\TextInput::make('quantity')
                            ->label('Quantità')
                            ->required()
                            ->numeric()
                            ->default(1),

                        Forms\Select::make('unit')
                            ->label('Unità di misura')
                            ->options(ProjectService::getUnitOptions())
                            ->native(false),

                        Forms\TextInput::make('estimated_cost')
                            ->label('Costo stimato')
                            ->numeric()
                            ->prefix('€'),

                        Forms\Select::make('status')
                            ->label('Stato')
                            ->options(ProjectService::getStatusOptions())
                            ->required()
                            ->default(ProjectService::STATUS_PENDING),
                    ])->columns(2),

                Section::make('Tempi')
                    ->schema([
                        Forms\DatePicker::make('needed_from')
                            ->label('Necessario dal'),

                        Forms\DatePicker::make('needed_until')
                            ->label('Fino al'),
                    ])->columns(2),

                Section::make('Note e Specifiche')
                    ->schema([
                        Forms\Textarea::make('notes')
                            ->label('Note')
                            ->maxLength(65535)
                            ->columnSpanFull(),

                        Forms\KeyValue::make('specifications')
                            ->label('Specifiche')
                            ->keyLabel('Nome')
                            ->valueLabel('Valore')
                            ->reorderable()
                            ->columnSpanFull(),
                    ])
                    ]);

    }
}
