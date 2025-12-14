<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RolesRelationManager extends RelationManager
{
    protected static string $relationship = 'roles';
    protected static ?string $navigationLabel = 'Ruoli';
    protected static ?string $modelLabel = 'Ruolo';
    protected static ?string $pluralModelLabel = 'Ruoli';
    protected static UnitEnum|string|null $navigationGroup = 'Produzione';
    protected static ?int $navigationSort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
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
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
