<?php

namespace App\Filament\Resources\ProjectLocations\Tables;

use App\Models\ProjectLocation;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\IconColumn;
use App\Enums\ProjectLocationStatus;


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
                ->label('Set')
                ->searchable()
                ->sortable(),

            TextColumn::make('city')
                ->label('Città')
                ->searchable()
                ->sortable(),

      TextColumn::make('location_type')
    ->label('Tipologia')
    ->badge()
    ->formatStateUsing(fn ($state): string => is_object($state) ? $state->value : (ProjectLocation::getLocationTypeOptions()[$state] ?? $state))
    ->searchable()
    ->sortable(),

            TextColumn::make('shooting_date')
                ->label('Data riprese')
                ->date()
                ->sortable(),

        TextColumn::make('status')
    ->label('Stato')
    ->badge()
    ->formatStateUsing(fn ($state): string => $state instanceof ProjectLocationStatus
        ? $state->getLabel()
        : (ProjectLocationStatus::tryFrom($state)?->getLabel() ?? $state))
    ->color(fn ($state) => $state instanceof ProjectLocationStatus
        ? $state->getColor()
        : (ProjectLocationStatus::tryFrom($state)?->getColor() ?? 'gray'))
    ->searchable()
    ->sortable(),
                IconColumn::make('is_open')
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
            SelectFilter::make('status')
                ->label('Stato')
                ->options(ProjectLocation::getStatusOptions()),


            SelectFilter::make('location_type')
                ->label('Tipologia')
                ->options(ProjectLocation::getLocationTypeOptions()),

            Filter::make('needs_permission')
                ->label('Richiede autorizzazione')
                ->query(fn (Builder $query): Builder => $query->where('permission_required', true)),

            Filter::make('upcoming_shootings')
                ->label('Prossime riprese')
                ->query(fn (Builder $query): Builder => $query->whereDate('shooting_date', '>=', now()))
                ->toggle(),
        ])
        ->actions([

        ])
        ->bulkActions([

        ])
        ->defaultSort('shooting_date', 'asc')
        ->emptyStateHeading('Nessun set presente')
        ->emptyStateDescription('Crea il tuo primo set per iniziare');
}
}
