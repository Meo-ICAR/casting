<?php

namespace App\Filament\Resources\Roles\Tables;


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

class RolesTableAlt
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome Ruolo')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('project.title')
                    ->label('Progetto')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('n')
                    ->label('N.')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->alignCenter(),

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
            ->recordActions([])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
