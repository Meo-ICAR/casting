<?php

namespace App\Filament\Resources\Roles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project.title')
                    ->label('Progetto')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('name')
                    ->label('Nome Ruolo')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('salary_range')
                    ->label('Compenso')
                    ->getStateUsing(function ($record) {
                        if ($record->salary_min && $record->salary_max) {
                            return '€' . number_format($record->salary_min, 0, ',', '.') . ' - €' . number_format($record->salary_max, 0, ',', '.');
                        } elseif ($record->salary_min) {
                            return 'Da €' . number_format($record->salary_min, 0, ',', '.');
                        } elseif ($record->salary_max) {
                            return 'Fino a €' . number_format($record->salary_max, 0, ',', '.');
                        }
                        return 'Non specificato';
                    })
                    ->placeholder('Non specificato')
                    ->toggleable(),

                TextColumn::make('applications_count')
                    ->label('Candidature')
                    ->counts('applications')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                IconColumn::make('is_open')
                    ->label('Aperto')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

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
                SelectFilter::make('project_id')
                    ->label('Progetto')
                    ->relationship('project', 'title')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('is_open')
                    ->label('Stato')
                    ->options([
                        1 => 'Aperto alle candidature',
                        0 => 'Chiuso alle candidature',
                    ])
                    ->native(false),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
