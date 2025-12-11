<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informazioni Progetto')
                    ->schema([
                        TextEntry::make('title')
                            ->label('Titolo')
                            ->icon('heroicon-o-film')
                            ->size('lg')
                            ->weight('bold'),

                        TextEntry::make('owner.name')
                            ->label('Casting Director')
                            ->icon('heroicon-o-user'),

                        TextEntry::make('type')
                            ->label('Tipo Progetto')
                            ->badge()
                            ->formatStateUsing(fn ($state) => match($state) {
                                'feature_film' => 'Film Lungometraggio',
                                'commercial' => 'Spot Pubblicitario',
                                'tv_series' => 'Serie TV',
                                'short' => 'Cortometraggio',
                                'documentary' => 'Documentario',
                                'web_series' => 'Web Series',
                                default => ucfirst($state),
                            })
                            ->color(fn ($state) => match($state) {
                                'feature_film' => 'success',
                                'tv_series' => 'info',
                                'commercial' => 'warning',
                                default => 'gray',
                            }),

                        TextEntry::make('status')
                            ->label('Stato')
                            ->badge()
                            ->formatStateUsing(fn ($state) => match($state) {
                                'casting' => 'In Casting',
                                'production' => 'In Produzione',
                                'wrapped' => 'Completato',
                                'cancelled' => 'Annullato',
                                default => ucfirst($state),
                            })
                            ->color(fn ($state) => match($state) {
                                'casting' => 'info',
                                'production' => 'warning',
                                'wrapped' => 'success',
                                'cancelled' => 'danger',
                                default => 'gray',
                            }),

                TextEntry::make('production_company')
                            ->label('Casa di Produzione')
                            ->placeholder('Non specificata')
                            ->icon('heroicon-o-building-office'),

                TextEntry::make('start_date')
                            ->label('Data Inizio Produzione')
                            ->date('d/m/Y')
                            ->placeholder('Non specificata')
                            ->icon('heroicon-o-calendar'),

                        TextEntry::make('roles_count')
                            ->label('Numero Ruoli')
                            ->counts('roles')
                            ->badge()
                            ->color('gray')
                            ->icon('heroicon-o-user-group'),

                TextEntry::make('created_at')
                            ->label('Creato il')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-o-clock'),

                TextEntry::make('updated_at')
                            ->label('Ultimo Aggiornamento')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-o-clock'),
                    ])
                    ->columns(2),

                Section::make('Descrizione')
                    ->schema([
                        TextEntry::make('description')
                            ->label('Descrizione Progetto')
                            ->placeholder('Nessuna descrizione disponibile')
                            ->columnSpanFull()
                            ->markdown(),
                    ]),
            ]);
    }
}
