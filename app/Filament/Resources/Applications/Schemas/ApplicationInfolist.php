<?php

namespace App\Filament\Resources\Applications\Schemas;

use App\Enums\ApplicationStatus;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ApplicationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informazioni Candidatura')
                    ->schema([
                        TextEntry::make('role.name')
                            ->label('Ruolo')
                            ->icon('heroicon-o-user-group'),

                        TextEntry::make('role.project.title')
                            ->label('Progetto')
                            ->icon('heroicon-o-film'),

                        TextEntry::make('status')
                            ->label('Stato')
                            ->badge()
                            ->formatStateUsing(fn ($state) => ($state instanceof ApplicationStatus ? $state : ApplicationStatus::from($state))->getLabel())
                            ->color(fn ($state) => match($state instanceof ApplicationStatus ? $state : ApplicationStatus::from($state)) {
                                ApplicationStatus::PENDING => 'gray',
                                ApplicationStatus::UNDER_REVIEW => 'info',
                                ApplicationStatus::CALLBACK => 'warning',
                                ApplicationStatus::ACCEPTED => 'success',
                                ApplicationStatus::REJECTED => 'danger',
                            }),

                        TextEntry::make('created_at')
                            ->label('Data Candidatura')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-o-calendar'),

                        TextEntry::make('updated_at')
                            ->label('Ultimo Aggiornamento')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-o-clock'),
                    ])
                    ->columns(2),

                Section::make('Profilo Attore')
                    ->schema([
                        ImageEntry::make('profile.headshots')
                            ->label('Foto Profilo')
                            ->getStateUsing(fn ($record) => $record->profile->getFirstMediaUrl('headshots', 'thumb'))
                            ->circular()
                            ->defaultImageUrl(url('/images/default-avatar.png'))
                            ->size(100),

                        TextEntry::make('profile.display_name')
                            ->label('Nome')
                            ->getStateUsing(fn ($record) =>
                                $record->profile->stage_name ??
                                $record->profile->user->name ??
                                'ID: ' . $record->profile_id
                            ),

                        TextEntry::make('profile.city')
                            ->label('Città')
                            ->placeholder('Non specificata'),

                        TextEntry::make('profile.height_cm')
                            ->label('Altezza')
                            ->suffix(' cm')
                            ->placeholder('Non specificata'),

                        TextEntry::make('profile.age')
                            ->label('Età')
                            ->suffix(' anni')
                            ->placeholder('Non specificata'),
                    ])
                    ->columns(2),

                Section::make('Messaggi')
                    ->schema([
                        TextEntry::make('cover_letter')
                            ->label('Lettera di Presentazione')
                            ->placeholder('Nessun messaggio inviato')
                            ->columnSpanFull()
                            ->markdown(),

                        TextEntry::make('director_notes')
                            ->label('Note del Regista')
                            ->placeholder('Nessuna nota')
                            ->columnSpanFull()
                            ->markdown(),
                    ]),
            ]);
    }
}
