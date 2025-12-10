<php
// All'inizio del file assicurati di importare i componenti necessari
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Grid;
// ... altri import standard della risorsa

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Tabs::make('Profilo Attore')
                ->tabs([
                    // --- SCHEDA 1: Dati Generali ---
                    Tabs\Tab::make('Anagrafica & Base')
                        ->icon('heroicon-o-user')
                        ->schema([
                            Grid::make(2)->schema([
                                // Colleghiamo temporaneamente l'utente (in produzione questo si gestisce diversamente)
                                Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->required()
                                    ->searchable()
                                    ->label('Utente Collegato (Temp)'),

                                TextInput::make('stage_name')
                                    ->label('Nome D\'Arte')
                                    ->placeholder('Lascia vuoto se usi il tuo nome reale')
                                    ->maxLength(255),

                                DatePicker::make('birth_date')
                                    ->label('Data di Nascita')
                                    ->native(false) // Usa il datepicker JS di Filament, più carino
                                    ->displayFormat('d/m/Y')
                                    ->required(),

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
                                    TextInput::make('city')->label('Città Residenza')->required(),
                                    TextInput::make('province')->label('Prov (Sigla)')->maxLength(2),
                                    TextInput::make('country')->label('Nazione')->default('IT'),
                                ]),
                        ]),

                    // --- SCHEDA 2: Caratteristiche Fisiche (Qui inizia la magia del JSON) ---
                    Tabs\Tab::make('Aspetto Fisico')
                        ->icon('heroicon-o-face-smile')
                        ->schema([
                            Section::make('Dati Base')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('height_cm')
                                        ->label('Altezza (cm)')
                                        ->numeric()
                                        ->minValue(50)->maxValue(250)
                                        ->required(),
                                    TextInput::make('weight_kg')
                                        ->label('Peso (kg)')
                                        ->numeric(),
                                ]),

                            // NOTA BENE: Usiamo la "dot notation" (appearance.eyes)
                            // Filament popolerà automaticamente la colonna JSON 'appearance' nel DB.
                            Section::make('Dettagli Aspetto (JSON)')
                                ->description('Questi dati vengono salvati nel campo JSON "appearance".')
                                ->columns(3)
                                ->schema([
                                    Select::make('appearance.eyes')
                                        ->label('Colore Occhi')
                                        ->options(['blue'=>'Azzurri', 'green'=>'Verdi', 'brown'=>'Castani', 'black'=>'Neri', 'hazel'=>'Nocciola']),

                                    Select::make('appearance.hair_color')
                                        ->label('Colore Capelli')
                                        ->options(['blonde'=>'Biondi', 'brown'=>'Castani', 'black'=>'Neri', 'red'=>'Rossi', 'grey'=>'Grigi']),

                                    Select::make('appearance.skin')
                                        ->label('Carnagione')
                                        ->options(['fair'=>'Chiara', 'medium'=>'Media', 'olive'=>'Olivastra', 'dark'=>'Scura']),

                                    Select::make('appearance.ethnicity')
                                        ->label('Etnia Scenica')
                                        ->searchable()
                                        ->options([
                                            'caucasian' => 'Caucasica', 'mediterranean' => 'Mediterranea',
                                            'african' => 'Africana', 'asian' => 'Asiatica', 'hispanic' => 'Ispanica'
                                            // ... aggiungi altre
                                        ]),

                                    Forms\Components\Toggle::make('appearance.has_tattoos')
                                        ->label('Ha Tatuaggi visibili?'),
                                ]),
                        ]),

                    // --- SCHEDA 3: Skills e Misure ---
                    Tabs\Tab::make('Skills & Misure')
                        ->icon('heroicon-o-sparkles')
                        ->schema([
                            // Usiamo TagsInput per creare array di stringhe nei JSON
                            Section::make('Capacità (JSON capabilities)')
                                ->schema([
                                    TagsInput::make('capabilities.languages')
                                        ->label('Lingue Parlate')
                                        ->placeholder('Scrivi e premi invio (es. Inglese, Spagnolo)')
                                        ->suggestions(['Inglese', 'Francese', 'Spagnolo', 'Tedesco', 'Russo', 'Cinese']),

                                    TagsInput::make('capabilities.skills')
                                        ->label('Skills Specifiche')
                                        ->placeholder('Es. Equitazione, Scherma, Canto Lirico')
                                        ->separator(','),

                                    TagsInput::make('capabilities.driving_license')
                                        ->label('Patenti')
                                        ->suggestions(['AM', 'A1', 'A', 'B', 'C', 'D', 'Nautica']),
                                ]),

                            Section::make('Misure Sartoriali (JSON measurements)')
                                ->columns(4)
                                ->schema([
                                    TextInput::make('measurements.shoes')->label('Scarpe (EU)')->numeric(),
                                    TextInput::make('measurements.jacket')->label('Giacca/Taglia')->numeric(),
                                    TextInput::make('measurements.chest')->label('Torace/Seno (cm)')->numeric(),
                                    TextInput::make('measurements.waist')->label('Vita (cm)')->numeric(),
                                    TextInput::make('measurements.hips')->label('Fianchi (cm)')->numeric(),
                                ]),
                        ]),

                    // --- SCHEDA 4: Social e Contatti ---
                     Tabs\Tab::make('Social & Link')
                        ->icon('heroicon-o-link')
                        ->schema([
                             Section::make('Link Esterni (JSON socials)')
                                ->schema([
                                    TextInput::make('socials.instagram')
                                        ->label('Link Instagram')
                                        ->url()
                                        ->prefix('https://instagram.com/'),
                                    TextInput::make('socials.imdb')
                                        ->label('Link IMDb')
                                        ->url(),
                                    TextInput::make('socials.website')
                                        ->label('Sito Web Personale')
                                        ->url(),
                                ])
                        ])
                        Tabs\Tab::make('Media & Showreel')
    ->icon('heroicon-o-film')
    ->schema([
        Section::make('Materiale Fotografico')
            ->description('Carica le tue foto migliori (Headshots). La prima sarà la foto profilo.')
            ->schema([
                SpatieMediaLibraryFileUpload::make('headshots')
                    ->label('Headshots / Book')
                    ->collection('headshots') // Deve coincidere con il nome nel Model
                    ->multiple()
                    ->reorderable() // Permette di trascinare per cambiare l'ordine
                    ->maxFiles(10)
                    ->responsiveImages()
                    ->imageEditor() // Permette di ritagliare le foto direttamente in Filament!
                    ->columnSpanFull(),
            ]),

        Section::make('Video & Showreel')
            ->description('Carica i tuoi showreel o selftape (Max 50MB a video).')
            ->schema([
                SpatieMediaLibraryFileUpload::make('showreels')
                    ->label('Showreel Video')
                    ->collection('showreels')
                    ->multiple()
                    ->maxFiles(3)
                    ->maxSize(51200) // 50MB
                    ->acceptedFileTypes(['video/mp4', 'video/quicktime'])
                    ->columnSpanFull(),
            ]),
    ]),
                ]) // Fine Tabs
        ]);
}
