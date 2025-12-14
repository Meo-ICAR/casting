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

class FillQuotationForm extends QuotationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Preventivo')
                    ->schema([
                        Grid::make(2)->schema([
                            // Hidden fields that we need but don't want to show
                            Hidden::make('project_service_id'),
                            Hidden::make('service_id'),
                            Hidden::make('status'),

                            // Display project service info as read-only
                            TextInput::make('project_service_info')
                                ->label('Servizio del Progetto')
                                ->formatStateUsing(function ($record) {
                                    if (!$record->projectService) return '';
                                    $project = $record->projectService->project;
                                    $serviceType = $record->projectService->serviceType;
                                    $city = $record->projectService->city;

                                    return "{$project->title} - {$record->projectService->name} " .
                                           ($serviceType ? "({$serviceType->name})" : '') .
                                           ($city ? " - {$city}" : "");
                                })
                                ->disabled()
                                ->columnSpan(2),
                                    // Display project service info as read-only
                            TextInput::make('project_service_date')
                                ->label('Periodo')
                                ->formatStateUsing(function ($record) {
                                    if (!$record->projectService) return '';
                                     $n = $record->projectService->quantity;
                                    $dal = $record->projectService->needed_from;

                                    $al = $record->projectService->needed_until;

                                    return ( $n ? " - N. {$n}" : "") . " - " . ( $dal ? " - {$dal}" : "") . " - " . ( $al ? " - {$al}" : "");
                                })
                                ->disabled()
                                ->columnSpan(2),
                                // Add this after the project_service_info TextInput
Textarea::make('project_service_description')
    ->label('Descrizione')
    ->formatStateUsing(fn ($record) => $record->projectService?->description)
    ->disabled()
    ->columnSpanFull()
    ->rows(2),

                             TextInput::make('final_price')
                                ->label('Prezzo Finale (€)')
                                ->numeric()
                                ->prefix('€')
                                ->required()
                                ->columnSpan(1),



                            DatePicker::make('valid_until')
                                ->label('Valido fino al')
                                ->minDate(now())
                                ->columnSpan(1),



                            Textarea::make('notes')
                                ->label('Note')
                                ->columnSpanFull()
                                ->rows(3),

                            // Display status as read-only
                            TextInput::make('status_display')
                                ->label('Stato')
                                ->formatStateUsing(fn ($record) =>
                                    Quotation::getStatuses()[$record->status] ?? $record->status
                                )
                                ->disabled()
                                ->columnSpan(1),

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
