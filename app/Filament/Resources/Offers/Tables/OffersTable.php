<?php

namespace App\Filament\Resources\Offers\Tables;

use App\Filament\Resources\Offers\Tables\Columns;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;

class OffersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('projectLocation.project.title')
                    ->label('Produzione')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('projectLocation.name')
                    ->label('Set di Produzione')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('location.name')
                    ->label('Location')
                    ->searchable()
                    ->sortable(),
                 ImageColumn::make('location.headshots')
                    ->label('')
                    ->getStateUsing(fn ($record) => $record->location->getFirstMediaUrl('photos', 'thumb'))

                    ->defaultImageUrl(url('/images/default-avatar.png'))
                    ->size(40),


                TextColumn::make('price')
                    ->label('Prezzo')
                    ->money('EUR')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Stato')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'In attesa',
                        'accepted' => 'Accettata',
                        'rejected' => 'Rifiutata',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('valid_until')
                    ->label('Valida fino al')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                // Add filters if needed
            ])
            ->actions([
               // EditAction::make(),
                // DeleteAction::make(),
            ])
            ->bulkActions([
               // Tables\Actions\BulkActionGroup::make([
                //Tables\Actions\DeleteBulkAction::make(),
                //]),
            ]);
    }
}
