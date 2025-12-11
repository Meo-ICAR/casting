<?php

namespace App\Filament\Resources\Castings;

use App\Filament\Resources\Castings\Pages\ListCastings;
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


class CastingResource extends Resource
{
    protected static ?string $model = Profile::class;
     protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;
    protected static ?string $navigationLabel = 'Casting';
    protected static ?string $modelLabel = 'Casting';
    protected static ?string $pluralModelLabel = 'Casting';
    protected static UnitEnum|string|null $navigationGroup = 'Produzione';
    protected static ?int $navigationSort = 5;

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
            ->columns([
                Tables\Columns\ImageColumn::make('profile_photo')
                    ->label('')
                    ->getStateUsing(fn ($record) => $record->getFirstMediaUrl('headshots', 'thumb'))
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png')),

                Tables\Columns\TextColumn::make('stage_name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('age')
                    ->label('Età')
                    ->sortable()
                    ->suffix(' anni'),

                Tables\Columns\TextColumn::make('height_cm')
                    ->label('Altezza')
                    ->suffix(' cm')
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefono')
                    ->searchable()
                    ->url(fn ($record) => $record->whatsapp_url, true)
                    ->icon('heroicon-o-phone')
                    ->color('primary')
                    ->visible(fn () => auth()->user()->can('viewPhone', Profile::class)),
            ])
           ->filters([
            // Role requirements filter
            Tables\Filters\SelectFilter::make('role_requirements')
                ->label('Requisiti del Ruolo')
                ->options($roles)
                ->searchable()
                ->multiple()
                ->query(function (Builder $query, array $data) {
                    if (empty($data['values'])) {
                        return $query;
                    }
                    $roleIds = $data['values'];

                    // This will filter profiles that match the role requirements
                    return $query->where(function($q) use ($roleIds) {
                        \Log::info('Filtering for role IDs:', $roleIds);
                        foreach ($roleIds as $roleId) {
                            $role = Role::with('project')->find($roleId);
                             \Log::info('Processing role:', [
            'role_id' => $role->id,
            'role_name' => $role->name,
            'project' => $role->project ? $role->project->title : null,
            'gender_requirement' => $role->gender_requirement ?? 'not set'
        ]);
                            if ($role) {
                                // Example: Filter by gender if specified in role
                                if ($role->gender_requirement) {
                                    $q->where('gender', $role->gender_requirement);
                                }

                                // Example: Filter by age range if specified in role
                                if ($role->min_age) {
                                    $q->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= ?', [$role->min_age]);
                                }
                                if ($role->max_age) {
                                    $q->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) <= ?', [$role->max_age]);
                                }

                                // Add more role-based filters as needed
                                // For example: height, skills, etc.
                            }
                        }
                    });
                }),
            ])
            ->actions([
            //    Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Add bulk actions if needed
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
