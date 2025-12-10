<?php

namespace App\Filament\Resources\Profiles;

use App\Filament\Resources\Profiles\Pages;
use App\Models\Profile;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Carbon;
use BackedEnum;
use UnitEnum;
use Filament\Schemas\Schema;

class ProfileResource extends Resource
{
    protected static ?string $model = Profile::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Profili Attori';
    protected static ?string $modelLabel = 'Profilo Attore';
    protected static UnitEnum|string|null $navigationGroup = 'Gestione Casting';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Profilo Attore')
                    ->tabs([
                        // --- SCHEDA 1: Dati Generali ---
                        Tabs\Tab::make('Anagrafica & Base')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Grid::make(2)->schema([
                                    // Colleghiamo l'utente
                                    Select::make('user_id')
                                        ->relationship('user', 'name')
                                        ->required()
                                        ->searchable()
                                        ->label('Utente Collegato')
                                        ->preload()
                                        ->createOptionForm([
                                            TextInput::make('name')
                                                ->required()
                                                ->maxLength(255),
                                            TextInput::make('email')
                                                ->email()
                                                ->required()
                                                ->maxLength(255)
                                                ->unique(),
                                            TextInput::make('password')
                                                ->password()
                                                ->required()
                                                ->confirmed()
                                                ->maxLength(255),
                                            TextInput::make('password_confirmation')
                                                ->password()
                                                ->required()
                                                ->dehydrated(false),
                                        ]),

                                    TextInput::make('stage_name')
                                        ->label('Nome D\'Arte')
                                        ->placeholder('Lascia vuoto se usi il tuo nome reale')
                                        ->maxLength(255),

                                    DatePicker::make('birth_date')
                                        ->label('Data di Nascita')
                                        ->native(false)
                                        ->displayFormat('d/m/Y')
                                        ->required()
                                        ->maxDate(now()),

                                    Select::make('gender')
                                        ->label('Genere (per casting)')
                                        ->options([
                                            'male' => 'Uomo',
                                            'female' => 'Donna',
                                            'non_binary' => 'Non-Binary',
                                        ])
                                        ->required(),
                                ]),

                                Section::make('Localizzazione')
                                    ->columns(3)
                                    ->schema([
                                        TextInput::make('city')
                                            ->label('Città Residenza')
                                            ->required(),
                                        TextInput::make('province')
                                            ->label('Provincia (Sigla)')
                                            ->maxLength(2),
                                        TextInput::make('country')
                                            ->label('Nazione')
                                            ->default('IT')
                                            ->required(),
                                    ]),

                                // Toggle per visibilità e agenzia
                                Section::make('Stato')
                                    ->schema([
                                        Toggle::make('is_visible')
                                            ->label('Visibile nel portale')
                                            ->default(true),
                                        Toggle::make('is_represented')
                                            ->label('Rappresentato da agenzia')
                                            ->reactive(),
                                        TextInput::make('agency_name')
                                            ->label('Nome Agenzia')
                                            ->visible(fn (callable $get) => $get('is_represented'))
                                            ->maxLength(255),
                                    ]),
                            ]),

                        // --- SCHEDA 2: Caratteristiche Fisiche ---
                        Tabs\Tab::make('Aspetto Fisico')
                            ->icon('heroicon-o-face-smile')
                            ->schema([
                                Section::make('Dati Base')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('height_cm')
                                            ->label('Altezza (cm)')
                                            ->numeric()
                                            ->minValue(50)
                                            ->maxValue(250)
                                            ->required(),
                                        TextInput::make('weight_kg')
                                            ->label('Peso (kg)')
                                            ->numeric()
                                            ->minValue(20)
                                            ->maxValue(300),
                                    ]),

                                Section::make('Dettagli Aspetto')
                                    ->description('Questi dati vengono salvati nel campo JSON "appearance".')
                                    ->columns(3)
                                    ->schema([
                                        Select::make('appearance.eyes')
                                            ->label('Colore Occhi')
                                            ->options([
                                                'blue' => 'Azzurri',
                                                'green' => 'Verdi',
                                                'brown' => 'Castani',
                                                'black' => 'Neri',
                                                'hazel' => 'Nocciola'
                                            ]),

                                        Select::make('appearance.hair_color')
                                            ->label('Colore Capelli')
                                            ->options([
                                                'blonde' => 'Biondi',
                                                'brown' => 'Castani',
                                                'black' => 'Neri',
                                                'red' => 'Rossi',
                                                'grey' => 'Grigi'
                                            ]),

                                        Select::make('appearance.skin')
                                            ->label('Carnagione')
                                            ->options([
                                                'fair' => 'Chiara',
                                                'medium' => 'Media',
                                                'olive' => 'Olivastra',
                                                'dark' => 'Scura'
                                            ]),

                                        Select::make('appearance.ethnicity')
                                            ->label('Etnia Scenica')
                                            ->searchable()
                                            ->options([
                                                'caucasian' => 'Caucasica',
                                                'mediterranean' => 'Mediterranea',
                                                'african' => 'Africana',
                                                'asian' => 'Asiatica',
                                                'hispanic' => 'Ispanica'
                                            ]),

                                        Toggle::make('appearance.has_tattoos')
                                            ->label('Ha Tatuaggi visibili?'),
                                    ]),
                            ]),

                        // --- SCHEDA 3: Skills e Misure ---
                        Tabs\Tab::make('Skills & Misure')
                            ->icon('heroicon-o-sparkles')
                            ->schema([
                                Section::make('Capacità')
                                    ->schema([
                                        TagsInput::make('capabilities.languages')
                                            ->label('Lingue Parlate')
                                            ->placeholder('Scrivi e premi invio (es. Inglese, Spagnolo)')
                                            ->suggestions([
                                                'Italiano', 'Inglese', 'Francese',
                                                'Spagnolo', 'Tedesco', 'Russo', 'Cinese'
                                            ]),

                                        TagsInput::make('capabilities.skills')
                                            ->label('Skills Specifiche')
                                            ->placeholder('Es. Equitazione, Scherma, Canto Lirico')
                                            ->separator(','),

                                        TagsInput::make('capabilities.driving_license')
                                            ->label('Patenti')
                                            ->suggestions(['AM', 'A1', 'A', 'B', 'C', 'D', 'Nautica']),
                                    ]),

                                Section::make('Misure Sartoriali')
                                    ->columns(4)
                                    ->schema([
                                        TextInput::make('measurements.shoes')
                                            ->label('Scarpe (EU)')
                                            ->numeric()
                                            ->minValue(30)
                                            ->maxValue(55),
                                        TextInput::make('measurements.jacket')
                                            ->label('Giacca/Taglia')
                                            ->numeric()
                                            ->minValue(30)
                                            ->maxValue(70),
                                        TextInput::make('measurements.chest')
                                            ->label('Torace/Seno (cm)')
                                            ->numeric()
                                            ->minValue(50)
                                            ->maxValue(150),
                                        TextInput::make('measurements.waist')
                                            ->label('Vita (cm)')
                                            ->numeric()
                                            ->minValue(40)
                                            ->maxValue(150),
                                        TextInput::make('measurements.hips')
                                            ->label('Fianchi (cm)')
                                            ->numeric()
                                            ->minValue(50)
                                            ->maxValue(150),
                                    ]),
                            ]),

                        // --- SCHEDA 4: Social e Contatti ---
                        Tabs\Tab::make('Social & Link')
                            ->icon('heroicon-o-link')
                            ->schema([
                                Section::make('Link Esterni')
                                    ->schema([
                                        TextInput::make('socials.instagram')
                                            ->label('Link Instagram')
                                            ->url()
                                            ->prefix('https://instagram.com/'),
                                        TextInput::make('socials.imdb')
                                            ->label('Link IMDb')
                                            ->url()
                                            ->prefix('https://www.imdb.com/name/'),
                                        TextInput::make('socials.website')
                                            ->label('Sito Web Personale')
                                            ->url()
                                            ->prefix('https://'),
                                    ])
                            ]),

                        // --- SCHEDA 5: Media & Showreel ---
                        Tabs\Tab::make('Media & Showreel')
                            ->icon('heroicon-o-film')
                            ->schema([
                                Section::make('Materiale Fotografico')
                                    ->description('Carica le tue foto migliori (Headshots). La prima sarà la foto profilo.')
                                    ->schema([
                                        FileUpload::make('headshots')
                                            ->label('Headshots / Book')
                                            ->directory('profiles/headshots')
                                            ->image()
                                            ->imageEditor()
                                            ->multiple()
                                            ->reorderable()
                                            ->maxFiles(10)
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Video & Showreel')
                                    ->description('Carica i tuoi showreel o selftape (Max 50MB a video).')
                                    ->schema([
                                        FileUpload::make('showreels')
                                            ->label('Showreel Video')
                                            ->directory('profiles/showreels')
                                            ->acceptedFileTypes(['video/mp4', 'video/quicktime'])
                                            ->maxSize(51200) // 50MB
                                            ->multiple()
                                            ->maxFiles(3)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('profile_photo')
                    ->label('')
                    ->getStateUsing(fn ($record) => $record->getFirstMediaUrl('headshots', 'thumb'))
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png')),

                TextColumn::make('user.name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('stage_name')
                    ->label('Nome d\'Arte')
                    ->searchable(),

                TextColumn::make('age')
                    ->label('Età')
                    ->sortable()
                    ->suffix(' anni'),

                TextColumn::make('height_cm')
                    ->label('Altezza')
                    ->suffix(' cm')
                    ->sortable(),

                IconColumn::make('is_visible')
                    ->label('Visibile')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Creato il')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('gender')
                    ->label('Genere')
                    ->options([
                        'male' => 'Uomo',
                        'female' => 'Donna',
                        'non_binary' => 'Non-Binary',
                    ]),

                Filter::make('age_range')
                    ->form([
                        Forms\Components\TextInput::make('min_age')
                            ->label('Età minima')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                        Forms\Components\TextInput::make('max_age')
                            ->label('Età massima')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['min_age'],
                                fn (Builder $query, $minAge): Builder => $query->where('birth_date', '<=', now()->subYears($minAge)),
                            )
                            ->when(
                                $data['max_age'],
                                fn (Builder $query, $maxAge): Builder => $query->where('birth_date', '>=', now()->subYears($maxAge + 1)),
                            );
                    }),

                Filter::make('height_range')
                    ->form([
                        Forms\Components\TextInput::make('min_height')
                            ->label('Altezza minima (cm)')
                            ->numeric()
                            ->minValue(50)
                            ->maxValue(250),
                        Forms\Components\TextInput::make('max_height')
                            ->label('Altezza massima (cm)')
                            ->numeric()
                            ->minValue(50)
                            ->maxValue(250),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['min_height'],
                                fn (Builder $query, $minHeight): Builder => $query->where('height_cm', '>=', $minHeight),
                            )
                            ->when(
                                $data['max_height'],
                                fn (Builder $query, $maxHeight): Builder => $query->where('height_cm', '<=', $maxHeight),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // Aggiungi qui le relazioni se necessario
            // RelationManagers\ApplicationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProfiles::route('/'),
            'create' => Pages\CreateProfile::route('/create'),
            'view' => Pages\ViewProfile::route('/{record}'),
            'edit' => Pages\EditProfile::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user', 'media'])
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
