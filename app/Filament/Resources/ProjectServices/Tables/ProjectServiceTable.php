<?php

namespace App\Filament\Resources\ProjectServices\Tables;

use App\Models\ProjectService;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\IconColumn;

class ProjectServiceTable
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
                ->searchable(),

            TextColumn::make('serviceType.name')
                ->label('Tipo servizio')
                ->badge()
                ->searchable(),

            TextColumn::make('city')
                ->label('Città')
                ->searchable(),

            TextColumn::make('quantity')
                ->label('Q.tà')
                ->numeric()
                ->sortable(),

            TextColumn::make('status')
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
                IconColumn::make('is_open')
    ->label('Stato')
    ->boolean()
    ->trueIcon('heroicon-o-check-circle')
    ->falseIcon('heroicon-o-x-circle')
    ->trueColor('success')
    ->falseColor('danger'),

            TextColumn::make('needed_from')
                ->label('Dal')
                ->date()
                ->sortable(),
        ])
        ->filters([
            SelectFilter::make('service_type_id')
                ->label('Tipo servizio')
                ->relationship('serviceType', 'name'),
        ])
        ->actions([
            /*
            EditAction::make()
                ->label('Modifica'),
            DeleteAction::make()
                ->label('Elimina'),
            */
        ])
        ->bulkActions([
            BulkActionGroup::make([
                DeleteBulkAction::make()
                    ->label('Elimina selezionati'),
            ]),
        ])
        ->emptyStateHeading('Nessun servizio trovato')
        ->emptyStateDescription('Crea il tuo primo servizio per iniziare');

    }
}
