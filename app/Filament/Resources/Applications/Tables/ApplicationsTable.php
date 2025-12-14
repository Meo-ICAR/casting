<?php

namespace App\Filament\Resources\Applications\Tables;

use App\Enums\ApplicationStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('profile.headshots')
                    ->label('')
                    ->getStateUsing(fn ($record) => $record->profile->getFirstMediaUrl('headshots', 'thumb'))
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png'))
                    ->size(40),

                TextColumn::make('profile.display_name')
                    ->label('Attore')
                    ->getStateUsing(fn ($record) =>
                        $record->profile->stage_name ??
                        $record->profile->user->name ??
                        'ID: ' . $record->profile_id
                    )
                    ->searchable(query: function ($query, $search) {
                        return $query->whereHas('profile', function ($q) use ($search) {
                            $q->where('stage_name', 'like', "%{$search}%")
                              ->orWhereHas('user', function ($uq) use ($search) {
                                  $uq->where('name', 'like', "%{$search}%");
                              });
                        });
                    })
                    ->sortable(),

                TextColumn::make('role.name')
                    ->label('Ruolo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('role.project.title')
                    ->label('Progetto')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Stato')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ($state instanceof ApplicationStatus ? $state : ApplicationStatus::from($state))->getLabel())
                    ->color(fn ($state) => match($state instanceof ApplicationStatus ? $state : ApplicationStatus::from($state)) {
                        ApplicationStatus::PENDING => 'gray',
                        ApplicationStatus::UNDER_REVIEW => 'info',
                        ApplicationStatus::CALLBACK => 'warning',
                        ApplicationStatus::ACCEPTED => 'success',
                        ApplicationStatus::REJECTED => 'danger',
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('cover_letter')
                    ->label('Messaggio')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->cover_letter)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Data Candidatura')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Ultimo Aggiornamento')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Stato')
                    ->options([
                        ApplicationStatus::DISPONIBILITA->value => ApplicationStatus::DISPONIBILITA->getLabel(),
                        ApplicationStatus::PENDING->value => ApplicationStatus::PENDING->getLabel(),
                        ApplicationStatus::UNDER_REVIEW->value => ApplicationStatus::UNDER_REVIEW->getLabel(),
                        ApplicationStatus::CALLBACK->value => ApplicationStatus::CALLBACK->getLabel(),
                        ApplicationStatus::ACCEPTED->value => ApplicationStatus::ACCEPTED->getLabel(),
                        ApplicationStatus::REJECTED->value => ApplicationStatus::REJECTED->getLabel(),
                    ])
                    ->multiple(),

                SelectFilter::make('role_id')
                    ->label('Ruolo')
                    ->relationship('role', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('profile_id')
                    ->label('Attore')
                    ->relationship('profile', 'stage_name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
              //  ViewAction::make(),
              //  EditAction::make(),
              //  DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->paginationPageOptions([5, 10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->defaultSort('created_at', 'desc');
    }
}
