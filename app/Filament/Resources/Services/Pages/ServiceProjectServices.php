<?php

namespace App\Filament\Resources\Services\Pages;

use App\Filament\Resources\Quotations\QuotationResource;
use App\Filament\Resources\Services\ServiceResource;
use App\Filament\Resources\Services\Pages\EditService;
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
use Filament\Actions\Action;
use Filament\Actions\EditAction;

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

                    ->with(['project',  'quotations' => function ($query) {
                        $query->where('service_id', $this->record->id);
                    }])

            )
            ->columns([
                Tables\Columns\TextColumn::make('project.title')
                    ->label('Progetto')
                    ->searchable()
                    ->sortable(),
 Tables\Columns\TextColumn::make('quantity')

                    ->label('N.')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('needed_from')
                    ->label('Dal')
                    ->searchable()
                    ->sortable(),
                 Tables\Columns\TextColumn::make('needed_until')
                    ->label('Al')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('city')
                    ->label('Città')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('quotations.proposed_price')
                    ->label('Preventivo')
                    ->numeric()
                    ->prefix('€'),
                Tables\Columns\TextColumn::make('quotations.status')
                    ->label('Stato')
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
                Action::make('create_quotation')
                    ->label('Nuovo Preventivo')
                    ->hidden(fn (ProjectService $record): bool => $record->quotations->isNotEmpty())
                    ->action(function (ProjectService $record) {
                        // Create a new quotation for this project service
                        $quotation = Quotation::create([
                            'project_service_id' => $record->id,
                            'service_id' => $resource->id,
                            'status' => Quotation::STATUS_PROPOSAL,
                            'proposed_price' => 0.00,
                        ]);

                        // Redirect to fill the newly created quotation
                        return redirect(QuotationResource::getUrl('fill', ['record' => $quotation]));
                    })
                    ->icon('heroicon-o-document-plus')
                    ->button()
                    ->color('primary'),
            ])
            ->bulkActions([
                // Add any bulk actions you need
            ]);
    }

    public function getTitle(): string
    {
        return "Elenco richieste di preventivo per: {$this->record->name}";
    }

    public function getBreadcrumb(): string
    {
        return 'Preventivazione';
    }

    protected function getHeaderActions(): array
    {
        return [
        \Filament\Actions\Action::make('editService')
            ->label('Modifica')
            ->icon('heroicon-o-pencil-square')
              ->url(fn () => \App\Filament\Resources\Services\ServiceResource::getUrl('edit', ['record' => $this->record->id]))

           ->color('primary'),
    ];
    }
}
