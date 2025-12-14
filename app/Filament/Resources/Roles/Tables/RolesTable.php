<?php

namespace App\Filament\Resources\Roles\Tables;

use App\Filament\Resources\Applications\ApplicationResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project.title')
                    ->label('Progetto')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('name')
                    ->label('Nome Ruolo')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('city')
                    ->label('Città')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Non specificata')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('scene_nudo')
                    ->label('Nudo')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'no' => 'No',
                        'parziale' => 'Parziale',
                        'si' => 'Sì',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'no' => 'success',
                        'parziale' => 'warning',
                        'si' => 'danger',
                        default => 'gray',
                    })
                    ->sortable()
                    ->searchable(),

                TextColumn::make('n')
                    ->label('N.')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('applications_count')
                    ->label('Candidature')
                    ->counts('applications')
                      ->getStateUsing(fn ($record) => $record->applications()->count())
    ->url(fn ($record) => $record->applications()->count() > 0
        ? ApplicationResource::getUrl('kanban.role', ['role' => $record->id])
        : null
    )
                    ->badge()
                       ->color(fn ($record) => $record->applications()->count() > 0 ? 'primary' : 'gray')
    ->icon(fn ($record) => $record->applications()->count() > 0 ? 'heroicon-o-arrow-top-right-on-square' : null)

                    ->sortable(),

                IconColumn::make('is_open')
                    ->label('Aperto')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                TextColumn::make('date_range')
                    ->label('Periodo')
                    ->getStateUsing(function ($record) {
                        if (!$record->start_date && !$record->end_date) {
                            return 'Non specificato';
                        }

                        $start = $record->start_date ? $record->start_date->format('d/m/Y') : 'ND';
                        $end = $record->end_date ? $record->end_date->format('d/m/Y') : 'ND';
                        return "$start - $end";
                    })
                    ->description(function ($record) {
                        if ($record->start_date && $record->end_date) {
                            return $record->start_date->diffForHumans($record->end_date);
                        } elseif ($record->start_date) {
                            return 'Inizia ' . $record->start_date->diffForHumans();
                        } elseif ($record->end_date) {
                            return 'Termina ' . $record->end_date->diffForHumans();
                        }
                        return '';
                    })
                    ->sortable(['start_date', 'end_date']),


            ])
            ->filters([
                SelectFilter::make('project_id')
                    ->label('Progetto')
                    ->relationship('project', 'title')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('is_open')
                    ->label('Stato')
                    ->options([
                        1 => 'Aperto alle candidature',
                        0 => 'Chiuso alle candidature',
                    ])
                    ->native(false),
            ])
            ->recordActions([

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                   // DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
