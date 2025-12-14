<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use App\Models\Company;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informazioni Utente')
                    ->schema([
                        Grid::make(2)->schema([
                TextInput::make('name')
                                ->label('Nome')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('last_name')
                                ->label('Cognome')
                                ->maxLength(255),

                TextInput::make('email')
                                ->label('Email')
                    ->email()
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true),

                            Select::make('role')
                                ->label('Ruolo')
                                ->options(\App\Enums\UserRole::options())
                                ->enum(\App\Enums\UserRole::class)
                                ->required()
                                ->default(\App\Enums\UserRole::ACTOR->value)
                                ->native(false)
                                ->helperText('Ruolo dell\'utente nel sistema'),

                            Select::make('company_id')
                                ->label('Casa')
                                ->relationship('company', 'name')
                                ->searchable()
                                ->preload()
                                ->createOptionForm([
                                    TextInput::make('name')
                                        ->label('Nome Casa')
                                        ->required()
                                        ->maxLength(255),
                                ])
                                ->helperText('Seleziona eventualmente casa di appartenenza'),
                        ]),
                    ]),

                Section::make('Password')
                    ->schema([
                TextInput::make('password')
                            ->label('Password')
                    ->password()
                            ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => \Hash::make($state))
                            ->maxLength(255)
                            ->helperText('Lascia vuoto per mantenere la password attuale durante la modifica'),
                    ]),
            ]);
    }
}
