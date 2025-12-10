<?php

namespace App\Filament\Resources\Locations\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Location Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('city')
                                    ->required()
                                    ->maxLength(100),
                                TextInput::make('province')
                                    ->maxLength(2),
                                TextInput::make('postal_code')
                                    ->maxLength(10),
                                Select::make('country')
                                    ->options([
                                        'IT' => 'Italy',
                                        'US' => 'United States',
                                        'GB' => 'United Kingdom',
                                        'DE' => 'Germany',
                                        'FR' => 'France',
                                        'ES' => 'Spain',
                                    ])
                                    ->default('IT')
                                    ->required(),
                                Toggle::make('is_active')
                                    ->default(true)
                                    ->required(),
                            ]),
                        TextInput::make('address')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->columnSpanFull(),
                    ]),

                Section::make('Location Photos')
                    ->schema([
                        FileUpload::make('photos')
                            ->multiple()
                            ->image()
                            ->directory('locations')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                null,
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->maxSize(10240) // 10MB
                            ->reorderable()
                            ->appendFiles()
                            ->downloadable()
                            ->openable()
                            ->previewable(true)
                            ->columnSpanFull(),
                    ]),

                Section::make('Contact Information')
                    ->schema([
                        TextInput::make('contact_person')
                            ->maxLength(255),
                        TextInput::make('contact_phone')
                            ->tel()
                            ->maxLength(50),
                        TextInput::make('contact_email')
                            ->email()
                            ->maxLength(255),
                    ]),

                Section::make('Location on Map')
                    ->description('Add coordinates to show this location on the map')
                    ->schema([
                        TextInput::make('latitude')
                            ->numeric()
                            ->reactive()
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                if (is_numeric($state) && ($state < -90 || $state > 90)) {
                                    $set('latitude', min(90, max(-90, (float) $state)));
                                }
                            }),
                        TextInput::make('longitude')
                            ->numeric()
                            ->reactive()
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                if (is_numeric($state) && ($state < -180 || $state > 180)) {
                                    $set('longitude', min(180, max(-180, (float) $state)));
                                }
                            }),
                    ]),

                Section::make('Additional Information')
                    ->schema([
                        Textarea::make('features')
                            ->columnSpanFull()
                            ->helperText('Enter features as a JSON object, e.g. {"parking": true, "indoor": false}'),
                        Textarea::make('notes')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
