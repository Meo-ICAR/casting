<?php

namespace App\Filament\Resources\Quotations\Tables;

use App\Models\Quotation;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class QuotationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('projectService.project.title')
                    ->label('Progetto')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Quotation $record) => $record->projectService?->project?->production_company)
                    ->wrap(),

                TextColumn::make('projectService.name')
                    ->label('Servizio Progetto')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Quotation $record) => $record->projectService?->serviceType?->name)
                    ->wrap(),

                TextColumn::make('service.name')
                    ->label('Servizio')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Quotation $record) => $record->service?->contact_name)
                    ->wrap(),

                TextColumn::make('status')
                    ->label('Stato')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Quotation::getStatuses()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        Quotation::STATUS_ACCEPTED => 'success',
                        Quotation::STATUS_REJECTED => 'danger',
                        Quotation::STATUS_NEGOTIATION => 'warning',
                        Quotation::STATUS_REVIEW => 'info',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('proposed_price')
                    ->label('Prezzo Proposto')
                    ->money('EUR')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('final_price')
                    ->label('Prezzo Finale')
                    ->money('EUR')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('valid_until')
                    ->label('Valido fino al')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('projectService.project')
                    ->label('Progetto')
                    ->relationship('projectService.project', 'title')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('service')
                    ->label('Servizio')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('status')
                    ->label('Stato')
                    ->options(Quotation::getStatuses())
                    ->multiple(),
            ])
            ->actions([
              //  ViewAction::make(),
              //  EditAction::make(),
              //  DeleteAction::make(),
            ])
            ->bulkActions([
            //    BulkActionGroup::make([
            //        DeleteBulkAction::make(),
            //        ForceDeleteBulkAction::make(),
            //        RestoreBulkAction::make(),
            //    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
