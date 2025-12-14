<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('last_name')
                    ->label('Cognome')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-envelope'),

                TextColumn::make('role')
                    ->label('Ruolo')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state instanceof \App\Enums\UserRole ? $state->getLabel() : \App\Enums\UserRole::from($state)->getLabel())
                    ->color(fn ($state) => $state instanceof \App\Enums\UserRole ? $state->getColor() : \App\Enums\UserRole::from($state)->getColor())
                    ->icon(fn ($state) => $state instanceof \App\Enums\UserRole ? $state->getIcon() : \App\Enums\UserRole::from($state)->getIcon())
                    ->searchable()
                    ->sortable(),

                IconColumn::make('has_profile')
                    ->label('Ha Profilo')
                    ->getStateUsing(fn ($record) => $record->profile !== null)
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
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
                SelectFilter::make('role')
                    ->label('Ruolo')
                    ->options(\App\Enums\UserRole::options())
                    ->multiple()
                    ->native(false),
            ])
            ->recordActions([

            ])
            ->toolbarActions([

            ])
            ->defaultSort('created_at', 'desc');
    }
}
