<?php

namespace App\Filament\Resources\Services\Pages;

use App\Filament\Resources\Quotations\QuotationResource;
use App\Filament\Resources\Services\ServiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRelatedRecords;
use App\Models\ProjectService;
use App\Models\Quotation;
use Filament\Actions;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords;

class ServiceProjectServices extends ListRecords
{

    use Tables\Concerns\InteractsWithTable;

    protected static string $resource = ServiceResource::class;
    // protected static string $view = 'filament.resources.service-resource.pages.service-project-services';

    public $record;

public function mount($record = null): void
{
    if ($record) {
        $this->record = \App\Models\Service::findOrFail($record);
    } else {
        $this->record = \App\Models\Service::findOrFail($this->record);
    }
    parent::mount();
}

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ProjectService::query()
                    ->where('service_type_id', $this->record->service_type_id)
                    ->with(['project', 'quotations'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('project.title')
                    ->label('Progetto')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Servizio')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('city')
                    ->label('Città')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Stato')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('quotations.status')
                    ->label('Stato Preventivo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'proposta' => 'info',
                        'in_esame' => 'warning',
                        'contrattazione' => 'primary',
                        'accettato' => 'success',
                        'scartato' => 'danger',
                        default => 'gray',
                    })
                    ->default('Nessun preventivo'),
            ])
            ->filters([
                // Add any filters you need
            ])
            ->actions([
                // Add any actions you need
            ])
            ->bulkActions([
                // Add any bulk actions you need
            ]);
    }

    public function getTitle(): string
    {
        return "Servizi per: {$this->record->name}";
    }

    public function getBreadcrumb(): string
    {
        return 'Servizi Progetto';
    }
}
