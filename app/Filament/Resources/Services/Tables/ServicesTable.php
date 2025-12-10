<?php

namespace App\Filament\Resources\Services\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('service_type')
                    ->label('Tipo Servizio')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'catering' => 'Catering',
                        'hair' => 'Parrucchiere',
                        'makeup' => 'Truccatrice',
                        'costume' => 'Sartoria',
                        'location' => 'Location',
                        'equipment' => 'Attrezzature',
                        'transport' => 'Trasporti',
                        'security' => 'Sicurezza',
                        'photography' => 'Fotografia',
                        'video' => 'Video',
                        'sound' => 'Audio',
                        'other' => 'Altro',
                        default => ucfirst($state),
                    })
                    ->color(fn ($state) => match($state) {
                        'catering' => 'success',
                        'hair' => 'info',
                        'makeup' => 'warning',
                        'costume' => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('contact_name')
                    ->label('Contatto')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('city')
                    ->label('Città')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->icon('heroicon-o-envelope')
                    ->toggleable(),

                TextColumn::make('phone')
                    ->label('Telefono')
                    ->searchable()
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('Attivo')
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
                SelectFilter::make('service_type')
                    ->label('Tipo Servizio')
                    ->options([
                        'catering' => 'Catering',
                        'hair' => 'Parrucchiere',
                        'makeup' => 'Truccatrice',
                        'costume' => 'Sartoria/Costumi',
                        'location' => 'Location',
                        'equipment' => 'Attrezzature',
                        'transport' => 'Trasporti',
                        'security' => 'Sicurezza',
                        'photography' => 'Fotografia',
                        'video' => 'Video',
                        'sound' => 'Audio',
                        'other' => 'Altro',
                    ])
                    ->multiple()
                    ->native(false),

                SelectFilter::make('is_active')
                    ->label('Stato')
                    ->options([
                        1 => 'Attivo',
                        0 => 'Non Attivo',
                    ])
                    ->native(false),

                SelectFilter::make('city')
                    ->label('Città')
                    ->multiple()
                    ->searchable(),
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
