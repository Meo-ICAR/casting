<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Titolo')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->production_company ?: null),

                TextColumn::make('owner.name')
                    ->label('Casting Director')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'feature_film' => 'Film',
                        'commercial' => 'Spot',
                        'tv_series' => 'Serie TV',
                        'short' => 'Corto',
                        'documentary' => 'Doc',
                        'web_series' => 'Web',
                        default => ucfirst($state),
                    })
                    ->color(fn ($state) => match($state) {
                        'feature_film' => 'success',
                        'tv_series' => 'info',
                        'commercial' => 'warning',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
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
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('roles_count')
                    ->label('Ruoli')
                    ->counts('roles')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label('Data Inizio')
                    ->date('d/m/Y')
                    ->sortable()
                    ->placeholder('Non specificata')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Creato il')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Aggiornato il')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipo Progetto')
                    ->options([
                        'feature_film' => 'Film Lungometraggio',
                        'commercial' => 'Spot Pubblicitario',
                        'tv_series' => 'Serie TV',
                        'short' => 'Cortometraggio',
                        'documentary' => 'Documentario',
                        'web_series' => 'Web Series',
                    ])
                    ->multiple(),

                SelectFilter::make('status')
                    ->label('Stato')
                    ->options([
                        'casting' => 'In Casting',
                        'production' => 'In Produzione',
                        'wrapped' => 'Completato',
                        'cancelled' => 'Annullato',
                    ])
                    ->multiple(),

                SelectFilter::make('user_id')
                    ->label('Casting Director')
                    ->relationship('owner', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
           //     ViewAction::make(),
           //     EditAction::make(),
           //     DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
