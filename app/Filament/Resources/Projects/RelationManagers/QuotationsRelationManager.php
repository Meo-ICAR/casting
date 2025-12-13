<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Models\Quotation;
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


class QuotationsRelationManager extends RelationManager
{
    protected static string $relationship = 'quotations';

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
            Select::make('service_id')
                ->label('Servizio')
                ->relationship(
                    name: 'service',
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

            Select::make('status')
                ->label('Stato')
                ->options(Quotation::getStatuses())
                ->default(Quotation::STATUS_PROPOSAL)
                ->required(),

            TextInput::make('proposed_price')
                ->label('Prezzo Proposto (€)')
                ->numeric()
                ->prefix('€')
                ->required(),

            TextInput::make('final_price')
                ->label('Prezzo Finale (€)')
                ->numeric()
                ->prefix('€'),

            DatePicker::make('valid_until')
                ->label('Valido fino al')
                ->minDate(now()),

            Textarea::make('notes')
                ->label('Note')
                ->columnSpanFull()
                ->rows(3),

            Textarea::make('rejection_reason')
                ->label('Motivo Rifiuto')
                ->visible(fn (callable $get) => $get('status') === Quotation::STATUS_REJECTED)
                ->columnSpanFull()
                ->rows(2),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('service.name')
            ->columns([
                TextColumn::make('service.name')
                    ->label('Servizio')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Quotation $record) => $record->service?->serviceType?->name),

                TextColumn::make('service.contact_name')
                    ->label('Contatto')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('service.phone')
                    ->label('Telefono')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Stato')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Quotation::getStatuses()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        Quotation::STATUS_ACCEPTED => 'success',
                        Quotation::STATUS_REJECTED => 'danger',
                        Quotation::STATUS_NEGOTIATION => 'warning',
                        Quotation::STATUS_REVIEW => 'info',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('proposed_price')
                    ->label('Prezzo Proposto')
                    ->money('EUR')
                    ->sortable(),

                TextColumn::make('final_price')
                    ->label('Prezzo Finale')
                    ->money('EUR')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('valid_until')
                    ->label('Valido fino al')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('service.service_type_id')
                    ->label('Tipo Servizio')
                    ->options(ServiceType::query()->pluck('name', 'id'))
                    ->searchable(),

                SelectFilter::make('status')
                    ->label('Stato')
                    ->options(Quotation::getStatuses())
                    ->multiple(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Nuovo Preventivo')
                    ->modalHeading('Crea Nuovo Preventivo')
                    ->using(function (array $data, string $model): Model {
                        $data['project_id'] = $this->getOwnerRecord()->id;
                        return $model::create($data);
                    }),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make()
                    ->form([
                        Select::make('status')
                            ->label('Stato')
                            ->options(Quotation::getStatuses())
                            ->required(),

                        TextInput::make('proposed_price')
                            ->label('Prezzo Proposto (€)')
                            ->numeric()
                            ->prefix('€'),

                        TextInput::make('final_price')
                            ->label('Prezzo Finale (€)')
                            ->numeric()
                            ->prefix('€'),

                        DatePicker::make('valid_until')
                            ->label('Valido fino al'),

                        Textarea::make('notes')
                            ->label('Note')
                            ->columnSpanFull(),

                        Textarea::make('rejection_reason')
                            ->label('Motivo Rifiuto')
                            ->visible(fn (callable $get) => $get('status') === Quotation::STATUS_REJECTED)
                            ->columnSpanFull(),
                    ]),
                DeleteAction::make(),
            ], position: ActionsPosition::BeforeCells)
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
