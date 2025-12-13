<?php

namespace App\Filament\Resources\ProjectLocations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;


class ProjectLocationsTable
{
   public static function configure(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('project.title')
                ->label('Progetto')
                ->sortable()
                ->searchable(),

            TextColumn::make('name')
                ->label('Nome')
                ->searchable()
                ->sortable(),

            TextColumn::make('city')
                ->label('Città')
                ->searchable()
                ->sortable(),

            TextColumn::make('location_type')
                ->label('Tipologia')
                ->badge()
                ->formatStateUsing(fn (string $state): string => ProjectLocation::getLocationTypeOptions()[$state] ?? $state)
                ->searchable()
                ->sortable(),

            TextColumn::make('shooting_date')
                ->label('Data riprese')
                ->date()
                ->sortable(),

            TextColumn::make('status')
                ->label('Stato')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    ProjectLocation::STATUS_PENDING => 'gray',
                    ProjectLocation::STATUS_REQUESTED => 'info',
                    ProjectLocation::STATUS_CONFIRMED => 'success',
                    ProjectLocation::STATUS_COMPLETED => 'primary',
                    ProjectLocation::STATUS_CANCELLED => 'danger',
                    default => 'gray',
                })
                ->sortable(),
                Tables\Columns\IconColumn::make('is_open')
    ->label('Stato')
    ->boolean()
    ->trueIcon('heroicon-o-check-circle')
    ->falseIcon('heroicon-o-x-circle')
    ->trueColor('success')
    ->falseColor('danger'),

            IconColumn::make('permission_required')
                ->label('Autorizzazione')
                ->boolean()
                ->sortable(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('status')
                ->label('Stato')
                ->options(ProjectLocation::getStatusOptions()),


            Tables\Filters\SelectFilter::make('location_type')
                ->label('Tipologia')
                ->options(ProjectLocation::getLocationTypeOptions()),

            Tables\Filters\Filter::make('needs_permission')
                ->label('Richiede autorizzazione')
                ->query(fn (Builder $query): Builder => $query->where('permission_required', true)),

            Tables\Filters\Filter::make('upcoming_shootings')
                ->label('Prossime riprese')
                ->query(fn (Builder $query): Builder => $query->whereDate('shooting_date', '>=', now()))
                ->toggle(),
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
                    ->label('Elimina selezionate'),
            ]),
        ])
        ->defaultSort('shooting_date', 'asc')
        ->emptyStateHeading('Nessuna location presentes')
        ->emptyStateDescription('Crea la tua prima location per iniziare');
}
}
