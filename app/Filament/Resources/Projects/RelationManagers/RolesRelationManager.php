<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class RolesRelationManager extends RelationManager
{
    protected static string $relationship = 'roles';

    protected static ?string $recordTitleAttribute = 'name';

    // NOTA: Qui cambiamo la firma da form(Form $form): Form a form(Schema $schema): Schema
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nome Ruolo'),

                Forms\Components\Textarea::make('description')
                    ->label('Descrizione')
                    ->columnSpanFull(),

                // Grid per i requisiti JSON
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('requirements.age_min')
                        ->numeric()
                        ->label('Età Minima'),
                    Forms\Components\TextInput::make('requirements.age_max')
                        ->numeric()
                        ->label('Età Massima'),
                    Forms\Components\Select::make('requirements.gender')
                        ->options([
                            'male' => 'Uomo',
                            'female' => 'Donna',
                        ])
                        ->label('Genere Richiesto'),
                ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('description')->limit(50),
                Tables\Columns\TextColumn::make('applications_count')
                    ->counts('applications')
                    ->label('Candidati')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
