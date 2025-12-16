<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Enums\ProjectLocationStatus;
use App\Enums\ProjectLocationType;
use App\Models\ProjectLocation;
use App\Models\ServiceType;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use UnitEnum;
use Filament\Support\Icons\Heroicon;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;

class ProjectLocationRelationManager extends RelationManager
{
    protected static string $relationship = 'projectLocations';

    protected static ?string $title = 'Sets';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFilm;
    protected static ?string $navigationLabel = 'Sets';
    protected static ?string $modelLabel = 'Set';
    protected static ?string $pluralModelLabel = 'Sets';
    protected static UnitEnum|string|null $navigationGroup = 'Produzione';
    protected static ?int $navigationSort = 10;

   public function form(Schema $schema): Schema
    {
        return $schema->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),

                TextInput::make('city')
                    ->label('Città')
                    ->required(),

                Select::make('location_type')
                    ->label('Tipologia')
                    ->options(ProjectLocationType::class)
                    ->enum(ProjectLocationType::class)
                    ->required()
                    ->native(false),

                DatePicker::make('shooting_date')
                    ->label('Data riprese')
                    ->required(),

                Select::make('status')
                    ->label('Stato')
                    ->options(ProjectLocationStatus::class)
                    ->enum(ProjectLocationStatus::class)
                    ->default(ProjectLocationStatus::PENDING)
                    ->required()
                    ->native(false),

                Select::make('permission_required')
                    ->label('Richiede autorizzazione')
                    ->boolean()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        $query = $this->getTableQuery();

        // If user is a director, filter locations by company_id
        if (auth()->user()->isDirector() && !auth()->user()->isAdmin()) {
            $query->whereHas('project', function($q) {
                $q->where('company_id', auth()->user()->company_id);
            });
        }

        return $table
            ->query($query)
            ->recordTitleAttribute('name')
            ->columns([
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
                    ->formatStateUsing(fn (ProjectLocationType $state): string => $state->getLabel())
                    ->color(fn (ProjectLocationType $state): string => $state->getColor())
                    ->searchable()
                    ->sortable(),

                TextColumn::make('shooting_date')
                    ->label('Data riprese')
                    ->date()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Stato')
                    ->badge()
                    ->formatStateUsing(fn (ProjectLocationStatus $state): string => $state->getLabel())
                    ->color(fn (ProjectLocationStatus $state): string => $state->getColor())
                    ->sortable(),

                IconColumn::make('permission_required')
                    ->label('Autorizzazione')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                // Add filters if needed
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                // Add bulk actions if needed
            ])
            ->defaultSort('shooting_date', 'desc');

    }

    }
