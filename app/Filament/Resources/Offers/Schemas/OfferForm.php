<?php

namespace App\Filament\Resources\Offers\Schemas;

use App\Models\Location;
use App\Models\ProjectLocation;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class OfferForm
{
    public static function configure(Schema $schema): Schema
    {
         return $schema
            ->components([
            Select::make('project_location_id')
                ->label('Set di Produzione')
                ->options(ProjectLocation::all()->pluck('name', 'id'))
                ->searchable()
                ->required(),

            Select::make('location_id')
                ->label('Location')
                ->options(Location::all()->pluck('name', 'id'))
                ->searchable()
                ->required(),

            TextInput::make('price')
                ->label('Prezzo')
                ->numeric()
                ->prefix('€')
                ->required(),

            Select::make('status')
                ->label('Stato')
                ->options([
                    'pending' => 'In attesa',
                    'accepted' => 'Accettata',
                    'rejected' => 'Rifiutata',
                ])
                ->required()
                ->default('pending'),

            DatePicker::make('valid_until')
                ->label('Valida fino al')
                ->required()
                ->default(now()->addDays(30)),

            Textarea::make('notes')
                ->label('Note')
                ->columnSpanFull(),
        ]);
    }
}
