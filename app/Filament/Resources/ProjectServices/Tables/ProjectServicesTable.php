<?php

namespace App\Filament\Resources\ProjectServices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProjectServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
               Tables\Columns\TextColumn::make('project.name')
                ->label('Progetto')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('name')
                ->label('Nome')
                ->searchable(),

            Tables\Columns\TextColumn::make('serviceType.name')
                ->label('Tipo servizio')
                ->badge()
                ->searchable(),

            Tables\Columns\TextColumn::make('city')
                ->label('Città')
                ->searchable(),

            Tables\Columns\TextColumn::make('quantity')
                ->label('Q.tà')
                ->numeric()
                ->sortable(),

            Tables\Columns\TextColumn::make('unit')
                ->label('Unità')
                ->formatStateUsing(fn (string $state): string => ProjectService::getUnitOptions()[$state] ?? $state),

            Tables\Columns\TextColumn::make('estimated_cost')
                ->label('Costo stimato')
                ->money('EUR')
                ->sortable(),

            Tables\Columns\TextColumn::make('status')
                ->label('Stato')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    ProjectService::STATUS_PENDING => 'gray',
                    ProjectService::STATUS_REQUESTED => 'info',
                    ProjectService::STATUS_CONFIRMED => 'success',
                    ProjectService::STATUS_COMPLETED => 'primary',
                    ProjectService::STATUS_CANCELLED => 'danger',
                    default => 'gray',
                }),

            Tables\Columns\TextColumn::make('needed_from')
                ->label('Dal')
                ->date()
                ->sortable(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('status')
                ->label('Stato')
                ->options(ProjectService::getStatusOptions()),

            Tables\Filters\SelectFilter::make('service_type_id')
                ->label('Tipo servizio')
                ->relationship('serviceType', 'name'),
        ])
        ->actions([
            Tables\Actions\EditAction::make()
                ->label('Modifica'),
            Tables\Actions\DeleteAction::make()
                ->label('Elimina'),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()
                    ->label('Elimina selezionati'),
            ]),
        ])
        ->emptyStateHeading('Nessun servizio trovato')
        ->emptyStateDescription('Crea il tuo primo servizio per iniziare');

    }
}
