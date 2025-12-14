<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Models\ProjectSerivice;
use App\Models\ServiceType;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use UnitEnum;
use Filament\Support\Icons\Heroicon;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;


class ProjectSeriviceRelationManager extends RelationManager
{
    protected static string $relationship = 'projectServices';

    protected static ?string $title = 'Preventivi';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFilm;
    protected static ?string $navigationLabel = 'Preventivi';
    protected static ?string $modelLabel = 'Preventivo';
    protected static ?string $pluralModelLabel = 'Preventivi';
    protected static UnitEnum|string|null $navigationGroup = 'Produzione';
    protected static ?int $navigationSort = 10;

    public function form(Schema $schema): Schema
    {
        return $schema->components([
           Select::make('service_type_id')  // Changed from service_id to service_type_id
    ->label('Tipo Servizio')
    ->relationship(
        name: 'serviceType',  // Changed from service to serviceType
        titleAttribute: 'name',
        modifyQueryUsing: fn (Builder $query) => $query->where('is_active', true)
    )
                ->searchable()
                ->preload()
                ->required()
                ->columnSpanFull()
                ->createOptionForm([
                    // Add service creation form if needed
                ])
                ->afterStateUpdated(function (callable $set, $state) {
                    if ($service = \App\Models\Service::find($state)) {
                        $set('proposed_price', $service->price_range);
                    }
                }),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
          //  ->recordTitleAttribute('service.name')
            ->columns([
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


            TextColumn::make('needed_from')
                ->label('Dal')
                ->date()
                ->sortable(),
            ])
            ->actions([
                ViewAction::make(),
            ])
                ->defaultSort('created_at', 'desc');
    }
}
