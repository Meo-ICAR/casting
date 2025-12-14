<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
    return $schema
            ->components([
                Section::make('Informazioni Utente')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('name')
                                ->label('Nome'),
                            TextEntry::make('last_name')
                                ->label('Cognome')
                                ->placeholder('-'),
                            TextEntry::make('email')
                                ->label('Email'),
                            TextEntry::make('role')
                                ->label('Ruolo'),
                            TextEntry::make('company.name')
                                ->label('Azienda')
                                ->placeholder('Nessuna azienda associata'),
                        ]),
                    ])
                    ->columns(2),

                Section::make('Dati di Sistema')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Creato il')
                            ->dateTime('d/m/Y H:i')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('updated_at')
                            ->label('Aggiornato il')
                            ->dateTime('d/m/Y H:i')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('email_verified_at')
                            ->label('Email verificata')
                            ->dateTime('d/m/Y H:i')
                            ->placeholder('Non verificata')
                            ->columnSpanFull(),
                    ])
            ]);
    }
}
