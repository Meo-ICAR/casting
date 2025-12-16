<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use App\Models\Project;
use App\Filament\Resources\Roles\RoleResource;
use Filament\Actions\Action;

class ProjectsTableView
{
    public static function configure(Table $table): Table
    {

        return $table
               ->query(
            Project::query()
                ->with([
                    'roles' => fn($query) => $query->orderBy('n'), // Eager load roles ordered by n
                    'owner' // Eager load the owner relationship
                ])
                ->withCount('roles') // Preload the roles count
        )
           ->contentGrid([
            'md' => 2,
            'xl' => 3,
            '2xl' => 4,
        ])
            ->columns([
                  Stack::make([

 ImageColumn::make('thumbnail')
    ->label('')
    ->getStateUsing(fn ($record) => $record->getFirstMediaUrl('photos', 'preview'))
    ->defaultImageUrl(url('/images/default-avatar.png'))
    ->height(150 )  // Set fixed height
    ->width('50%') // Make it take full width of the card
    ->extraImgAttributes(['class' => 'object-cover rounded-t-xl']),

    // PANNELLO DATI
                Panel::make([
                    Stack::make([

                        // Riga 1: Nome (Grande) e Età (Badge)
                        Split::make([
                TextColumn::make('title')
                    ->label('Titolo')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description('Ricerca:'),


       ]),

                        // Riga 2: Altezza e Visibilità
                        Split::make([
                TextColumn::make('roles.fullrole')
                    ->label('Ruoli')

    ->html()
    ->wrap()

   ]),
      ]),
         ]),
            ]),


 ])
            ->filters([


                SelectFilter::make('status')
                    ->label('Stato')
                    ->options([
                        'casting' => 'In Casting',
                        'production' => 'In Produzione',
                        'wrapped' => 'Completato',
                        'cancelled' => 'Annullato',
                    ])
                    ->multiple(),


            ])

            ->defaultSort('created_at', 'desc')
           ->recordActions([
                Action::make('view')
                    ->url(fn (Project $record): string => RoleResource::getUrl('index', [
                        'filters' => [
                            'project_id' => [
                                'value' => $record->id,
                                'isActive' => true
                            ]
                        ]
                    ])
                //    ->openUrlInNewTab()
                    )
                ->label('Dettaglio Ruoli')
                ->icon('heroicon-o-eye')
                ->color('primary')
            ]);

    }
}
