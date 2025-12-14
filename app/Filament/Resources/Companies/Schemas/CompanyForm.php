<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informazioni Azienda')
                    ->schema([
                        TextInput::make('name')
                            ->label('Ragione Sociale')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Telefono')
                            ->tel()
                            ->maxLength(50),
                        TextInput::make('website')
                            ->label('Sito Web')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Indirizzo')
                    ->schema([
                        Textarea::make('address')
                            ->label('Indirizzo')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        TextInput::make('city')
                            ->label('Città')
                            ->maxLength(255),
                        TextInput::make('postal_code')
                            ->label('CAP')
                            ->maxLength(20),
                        TextInput::make('country')
                            ->label('Nazione')
                            ->maxLength(100),
                    ])
                    ->columns(3),

                Section::make('Dati Fiscali')
                    ->schema([
                        TextInput::make('vat_number')
                            ->label('Partita IVA')
                            ->maxLength(20),
                        TextInput::make('tax_code')
                            ->label('Codice Fiscale')
                            ->maxLength(16),
                        TextInput::make('pec')
                            ->label('PEC')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('sdi_code')
                            ->label('Codice SDI')
                            ->maxLength(10),
                    ])
                    ->columns(2),
            ]);
    }
}
