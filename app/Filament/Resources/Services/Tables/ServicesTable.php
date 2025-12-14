<?php

namespace App\Filament\Resources\Services\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Models\ServiceType;
use App\Filament\Resources\Services\ServiceResource;

class ServicesTable
{
    protected static string $resource = ServiceResource::class;

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('serviceType.name')
                    ->label('Tipo Servizio')
                    ->badge()
                    ->color('gray')
                    ->placeholder('Non specificato')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('contact_name')
                    ->label('Contatto')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('city')
                    ->label('Città')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->icon('heroicon-o-envelope')
                    ->toggleable(),

                TextColumn::make('phone')
                    ->label('Telefono')
                    ->searchable()
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('Attivo')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

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
                SelectFilter::make('service_type_id')
                    ->label('Tipo Servizio')
                    ->options(fn () => ServiceType::query()
                        ->where('is_active', true)
                        ->orderBy('name')
                        ->pluck('name', 'id'))
                    ->multiple()
                    ->native(false),

                SelectFilter::make('is_active')
                    ->label('Stato')
                    ->options([
                        1 => 'Attivo',
                        0 => 'Non Attivo',
                    ])
                    ->native(false),

                SelectFilter::make('city')
                    ->label('Città')
                    ->multiple()
                    ->searchable(),
            ])

            ->recordActions([
                 Action::make('projectServices')
                ->label('Richieste')
                ->icon('heroicon-o-document-chart-bar')
                   ->color('successs')
                 ->url(fn ($record) => static::$resource::getUrl('project-services', ['record' => $record])),

                /*
             Tables\Actions\Action::make('projectServices')
                ->label('Vedi Progetti')
                ->icon('heroicon-o-document-chart-bar')
                ->url(fn (Service $record): string => static::getUrl('project-services', ['record' => $record])),
            // ... other actions
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                */
            ])
            /*
            ->toolbarActions([

                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
                */
            ->defaultSort('name', 'desc');
    }
}
