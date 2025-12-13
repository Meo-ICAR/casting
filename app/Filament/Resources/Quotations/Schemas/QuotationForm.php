<?php

namespace App\Filament\Resources\Quotations\Schemas;

use App\Models\ProjectService;
use App\Models\Quotation;
use App\Models\Service;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class QuotationForm
{
    public static function configure(Schema $schema): Schema
    {
       return $schema
            ->components([
            Section::make('Informazioni Preventivo')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('project_service_id')
    ->label('Servizio del Progetto')
    ->relationship(
        name: 'projectService',
        titleAttribute: 'name',
        modifyQueryUsing: fn (Builder $query) => $query->whereHas('project', function($q) {
            $q->whereIn('status', ['casting', 'production']);
        })
    )
      ->getOptionLabelFromRecordUsing(fn (ProjectService $record) =>
            "{$record->project->title} - {$record->name} ({$record->serviceType?->name})" .
            ($record->city ? " - {$record->city}" : "")
        )
    ->searchable(['name', 'project.title', 'city'])
    ->preload()
    ->required()
    ->live()
 ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
            if ($projectService = ProjectService::with('serviceType')->find($state)) {
                $set('service_id', $projectService->service_type_id);
                $set('proposed_price', $projectService->estimated_cost);
                // Update city display when project service changes
                $set('project_service_city', $projectService->city);
            }
        }),


// Service Select - Now filtered by service type
Select::make('service_id')
    ->label('Servizio')
    ->relationship(
        name: 'service',
        titleAttribute: 'name',
         modifyQueryUsing: function (Builder $query, Get $get) {
                                    $projectServiceId = $get('project_service_id');
                                    $quotationId = $get('id');

                                    // If we have a project service, filter by its service type
                                    if ($projectServiceId) {
                                        $projectService = ProjectService::with('serviceType')->find($projectServiceId);
                                        if ($projectService?->service_type_id) {
                                            return $query->where('services.service_type_id', $projectService->service_type_id);
                                        }
                                    }

                                    // If editing, make sure to include the current service
                                    if ($quotationId) {
                                        $quotation = Quotation::find($quotationId);
                                        if ($quotation?->service_id) {
                                            $query->orWhere('id', $quotation->service_id);
                                        }
                                    }

     return $query;
                                }
                            )
                           ->getOptionLabelFromRecordUsing(fn (Service $record) =>
            "{$record->name}" .
            ($record->city ? " - {$record->city}" : "")
        )
        ->searchable(['name', 'city'])
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
            // Update service city display when service changes
            if ($service = Service::find($state)) {
                $set('service_city', $service->city);
            }
        }),

                        Select::make('status')
                            ->label('Stato')
                            ->options(Quotation::getStatuses())
                            ->default(Quotation::STATUS_PROPOSAL)
                            ->required()
                            ->columnSpan(1),

                        DatePicker::make('valid_until')
                            ->label('Valido fino al')
                            ->minDate(now())
                            ->columnSpan(1),

                        TextInput::make('proposed_price')
                            ->label('Prezzo Proposto (€)')
                            ->numeric()
                            ->prefix('€')
                            ->columnSpan(1),

                        TextInput::make('final_price')
                            ->label('Prezzo Finale (€)')
                            ->numeric()
                            ->prefix('€')
                            ->columnSpan(1),

                        Textarea::make('notes')
                            ->label('Note')
                            ->columnSpanFull()
                            ->rows(3),

                        Textarea::make('rejection_reason')
                            ->label('Motivo Rifiuto')
                            ->visible(fn (callable $get) => $get('status') === Quotation::STATUS_REJECTED)
                            ->columnSpanFull()
                            ->rows(2),
                    ]),
                ])
                ->columns(1),
        ]);
    }
}
