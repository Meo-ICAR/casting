<?php

namespace App\Filament\Resources\Applications\Schemas;

use App\Enums\ApplicationStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ApplicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    Select::make('role_id')
                        ->label('Ruolo')
                        ->relationship('role', 'name', fn ($query) => $query->where('is_open', true))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->helperText('Seleziona il ruolo per cui l\'attore si sta candidando'),

                    Select::make('profile_id')
                        ->label('Profilo Attore')
                        ->relationship('profile', 'stage_name', fn ($query) => $query->with('user'))
                        ->getOptionLabelFromRecordUsing(fn ($record) =>
                            ($record->stage_name ?? $record->user->name ?? 'ID: ' . $record->id) .
                            ($record->city ? ' (' . $record->city . ')' : '')
                        )
                        ->searchable()
                        ->preload()
                        ->required()
                        ->helperText('Seleziona il profilo dell\'attore che si candida'),
                ]),

                Section::make('Stato Candidatura')
                    ->schema([
                        Select::make('status')
                            ->label('Stato')
                            ->options([
                                ApplicationStatus::PENDING->value => ApplicationStatus::PENDING->label(),
                                ApplicationStatus::UNDER_REVIEW->value => ApplicationStatus::UNDER_REVIEW->label(),
                                ApplicationStatus::CALLBACK->value => ApplicationStatus::CALLBACK->label(),
                                ApplicationStatus::ACCEPTED->value => ApplicationStatus::ACCEPTED->label(),
                                ApplicationStatus::REJECTED->value => ApplicationStatus::REJECTED->label(),
                            ])
                            ->default(ApplicationStatus::PENDING->value)
                            ->required()
                            ->native(false),
                    ]),

                Section::make('Messaggi')
                    ->schema([
                        Textarea::make('cover_letter')
                            ->label('Lettera di Presentazione')
                            ->placeholder('Messaggio dell\'attore al casting director')
                            ->rows(4)
                            ->columnSpanFull()
                            ->helperText('Messaggio opzionale inviato dall\'attore insieme alla candidatura'),

                        Textarea::make('director_notes')
                            ->label('Note del Regista')
                            ->placeholder('Note private visibili solo al team di casting')
                            ->rows(4)
                            ->columnSpanFull()
                            ->helperText('Note interne per valutazione e organizzazione'),
                    ]),
            ]);
    }
}
