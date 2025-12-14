<?php

namespace App\Filament\Resources\Locations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Models\Location;

class LocationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label('')
                    ->getStateUsing(fn ($record) => $record->getFirstMediaUrl('photos', 'thumb'))
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png'))
                    ->size(50),

                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('city')
                    ->label('Città')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('province')
                    ->label('Prov.')
                    ->searchable()
                    ->sortable()
                    ->limit(3),

                TextColumn::make('country')
                    ->label('Nazione')
                    ->searchable()
                    ->sortable()
                    ->limit(3),

                TextColumn::make('contact_person')
                    ->label('Contatto')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('contact_phone')
                    ->label('Telefono')
                    ->searchable()
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('Attivo')
                    ->boolean()
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
                TrashedFilter::make(),
                SelectFilter::make('city')
                    ->label('Città')
                    ->options(fn () => Location::query()
                        ->whereNotNull('city')
                        ->distinct()
                        ->orderBy('city')
                        ->pluck('city', 'city'))
                    ->multiple()
                    ->searchable(),
                SelectFilter::make('is_active')
                    ->label('Stato')
                    ->options([
                        1 => 'Attivo',
                        0 => 'Non Attivo',
                    ])
                    ->native(false),
            ])
            ->recordActions([
                /*
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                */
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
