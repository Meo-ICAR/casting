<?php

namespace App\Filament\Resources\Castings;

use App\Filament\Resources\Castings\Pages\ListCastings;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Profile;
use App\Models\Role;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Console\Commands\SendWhatsAppMessages;
use Filament\Notifications\Notification;

class CastingResource extends Resource
{
    protected static ?string $model = Profile::class;
     protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;
    protected static ?string $navigationLabel = 'Casting';
    protected static ?string $modelLabel = 'Casting';
    protected static ?string $pluralModelLabel = 'Casting';
    protected static UnitEnum|string|null $navigationGroup = 'Produzione';
    protected static ?int $navigationSort = 6;

    public static function table(Table $table): Table
    {
        // Get all roles for the filter
        $roles = Role::with('project')
    ->get()
    ->mapWithKeys(fn($role) => [
        $role->id => $role->name . ' - ' . ($role->project->title ?? 'Nessun progetto')
    ])
    ->toArray();

    return $table
  ->selectable()  // Abilita la selezione multipla

        // 1. GRIGLIA RESPONSIVE
        ->contentGrid([
            'md' => 2,
            'xl' => 3,
            '2xl' => 4,
        ])

        // 2. LAYOUT CARD
        ->columns([
            \Filament\Tables\Columns\Layout\Stack::make([

                // FOTO COPERTINA
                \Filament\Tables\Columns\ImageColumn::make('profile_photo')
                    ->label('')
                    // Usiamo la logica per prendere l'immagine convertita (thumb)
                    ->getStateUsing(fn ($record) => $record->getFirstMediaUrl('headshots', 'thumb'))
                    ->height('250px')
                    ->width('100%')
                    ->extraImgAttributes(['class' => 'object-cover w-full rounded-t-xl']),

                // PANNELLO DATI
                \Filament\Tables\Columns\Layout\Panel::make([
                    \Filament\Tables\Columns\Layout\Stack::make([

                        // Riga 1: Nome (Grande) e Età (Badge)
                        \Filament\Tables\Columns\Layout\Split::make([
                            \Filament\Tables\Columns\TextColumn::make('stage_name')
                                ->weight('bold') // Usa stringa semplice
                                ->size('lg')     // CORRETTO: Usa stringa 'lg' invece della classe
                                ->searchable(),

                            \Filament\Tables\Columns\TextColumn::make('age')
                                ->formatStateUsing(fn ($state) => $state . ' anni')
                                ->badge()
                                ->color('gray')
                                ->alignEnd(),
                        ]),

                        // Riga 2: Altezza e Visibilità
                        \Filament\Tables\Columns\Layout\Split::make([
                            \Filament\Tables\Columns\TextColumn::make('height_cm')
                                ->formatStateUsing(fn ($state) => $state . ' cm')
                                ->icon('heroicon-m-arrows-up-down')
                                ->color('gray')
                                ->size('sm'), // CORRETTO: Usa stringa 'sm'

                            \Filament\Tables\Columns\TextColumn::make('scene_nudo')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'no' => 'gray',
                                    'parziale' => 'warning',
                                    'si' => 'success',
                                    default => 'gray',
                                })
                                ->formatStateUsing(fn (string $state): string => match ($state) {
                                    'no' => 'No Nudo',
                                    'parziale' => 'Nudo Parziale',
                                    'si' => 'Nudo Completo',
                                    default => $state,
                                })
                                ->alignEnd(),
                        ]),



                        // Riga 4: Telefono con WhatsApp
                          \Filament\Tables\Columns\Layout\Split::make([
                        \Filament\Tables\Columns\TextColumn::make('phone')
                            ->label('WhatsApp')
                            ->color('success')
                            ->url(fn ($record) => $record->getWhatsappUrl('Ciao! Puoi ricontattarci?'))
                            ->icon('heroicon-o-chat-bubble-oval-left-ellipsis')
                            ->openUrlInNewTab()

                      ]),
                    ])->extraAttributes(['class' => 'bg-white p-4 rounded-b-xl border-x border-b border-gray-200 dark:bg-gray-900 dark:border-gray-700']),
                ]),
            ]),
        ])

 // 3. FILTRI
            ->filters([
                Tables\Filters\SelectFilter::make('role_requirements')
                    ->label('Requisiti del Ruolo')
                    ->options($roles)
                    ->searchable()
                    ->multiple()
                    ->query(function (Builder $query, array $data) {
                        if (empty($data['values'])) return $query;

                        return $query->where(function($mainQuery) use ($data) {
                            foreach ($data['values'] as $roleId) {
                                $role = Role::find($roleId);
                                if (!$role) continue;
                                $req = $role->requirements ?? [];

                                $mainQuery->orWhere(function($q) use ($req) {
                                    if (!empty($req['gender'])) $q->where('gender', $req['gender']);
                                    if (!empty($req['age_min'])) $q->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= ?', [$req['age_min']]);
                                    if (!empty($req['age_max'])) $q->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) <= ?', [$req['age_max']]);
                                    if (!empty($req['height_min'])) $q->where('height_cm', '>=', $req['height_min']);
                                    if (!empty($req['height_max'])) $q->where('height_cm', '<=', $req['height_max']);
                                });
                            }
                        });
                    }),
            ])
// 4. AZIONI SINGOLE
            ->actions([
               //  Tables\Actions\ViewAction::make(),
            ])

            // 5. AZIONI DI GRUPPO (BULK)
              ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('whatsapp_bulk')
                        ->label('Chiedi disponibilità')
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->color('success')
                        ->action(function (Collection $records) {
        $numbers = $records
            ->filter(fn($record) => !empty($record->phone))
            ->pluck('phone')
            ->toArray();
        if (empty($numbers)) {
            Notification::make()
                ->title('Nessun numero di telefono valido trovato')
                ->danger()
                ->send();
            return;
        }
        SendWhatsAppMessages::sendToNumbers($numbers, 'Ciao! Ti contatto per il casting.');
    })
    ->deselectRecordsAfterCompletion()
                ]),
              ]);


    }

    public static function getRelations(): array
    {
        return [
            // Add relations if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCastings::route('/'),
        ];
    }
}
