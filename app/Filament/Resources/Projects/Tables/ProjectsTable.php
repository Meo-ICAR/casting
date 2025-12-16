<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Enums\ProjectType;
use App\Enums\ProjectStatus;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
         ImageColumn::make('thumbnail')
                    ->label('')
                    ->getStateUsing(fn ($record) => $record->getFirstMediaUrl('photos', 'thumb'))
                    ->defaultImageUrl(url('/images/default-avatar.png'))
                    ->size(50),

                TextColumn::make('title')
                    ->label('Titolo')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->production_company ?: null),

               TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ProjectType::from($state)->label())
                    ->color(fn ($state) => ProjectType::from($state)->color())
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Stato')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ProjectStatus::from($state)->label())
                    ->color(fn ($state) => ProjectStatus::from($state)->color())
                    ->searchable()
                    ->sortable(),

                TextColumn::make('roles_count')
                    ->label('Ruoli')
                    ->counts('roles')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label('Data Inizio')
                    ->date('d/m/Y')
                    ->sortable()
                    ->placeholder('Non specificata')
                    ->toggleable(),

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
                SelectFilter::make('type')
                    ->label('Tipo Progetto')
                    ->options(
                        collect(ProjectType::cases())
                            ->mapWithKeys(fn($type) => [$type->value => $type->filterLabel()])
                            ->toArray()
                    )
                    ->multiple(),

                SelectFilter::make('status')
                    ->label('Stato')
                    ->options(ProjectStatus::options())
                    ->multiple(),

                SelectFilter::make('user_id')
                    ->label('Casting Director')
                    ->relationship('owner', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
           //     ViewAction::make(),
           //     EditAction::make(),
           //     DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
