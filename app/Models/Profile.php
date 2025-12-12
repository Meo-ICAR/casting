<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Laravel\Scout\Searchable; // <--- Importante
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasWhatsapp; // <--- Importa il Trait


class Profile extends Model implements HasMedia // <--- 1. Implementa l'interfaccia
{
    use InteractsWithMedia; // <--- 2. Usa il Trait
    use Searchable; // <--- Aggiungi il Trait
    use HasWhatsapp; // <--- Attivalo qui

    protected $guarded = [];

    /**
     * Definisce quali dati vengono inviati a Meilisearch.
     */
    public function toSearchableArray(): array
    {
        // Carichiamo le relazioni se servono (es. nome utente)
        $this->loadMissing('user');

        return [
            'id' => $this->id,
            'name' => $this->stage_name ?? $this->user->name,
            'gender' => $this->gender,
            'age' => $this->age, // Usiamo l'accessor creato prima
            'height' => (int) $this->height_cm,

            // --- APPIATTIMENTO DEI JSON ---
            // Estraiamo i dati dai JSON e li mettiamo al primo livello
            // così sarà facilissimo filtrarli.

            'eye_color' => $this->appearance['eyes'] ?? null,
            'hair_color' => $this->appearance['hair_color'] ?? null,
            'ethnicity' => $this->appearance['ethnicity'] ?? null,

            // Per gli array (es. lingue), Meilisearch li gestisce benissimo
            'languages' => $this->capabilities['languages'] ?? [],
            'skills' => $this->capabilities['skills'] ?? [],

            // Utile per mostrare subito l'immagine nei risultati senza query al DB
            'thumbnail_url' => $this->getFirstMediaUrl('headshots', 'thumb'),
        ];
    }

// 3. Definisci le conversioni (Miniature)
    public function registerMediaConversions(Media $media = null): void
    {
        // Miniatura quadrata per le liste attori (Admin/Director view)
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->sharpen(10)
            ->nonQueued(); // Generala subito (o togli per usare le code)

        // Se è un video, estrai un fotogramma al secondo 10 come copertina
        $this->addMediaConversion('preview')
            ->width(640)
            ->height(360)
            ->extractVideoFrameAtSecond(10)
            ->performOnCollections('showreels');
    }

    // 4. Definisci le Collezioni
    public function registerMediaCollections(): void
    {
        // HEADSHOTS: Solo immagini, max 10 file
        $this->addMediaCollection('headshots')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->useDisk('public'); // O 's3' se usi AWS

        // SHOWREELS: Solo video, max 3 file
        $this->addMediaCollection('showreels')
            ->acceptsMimeTypes(['video/mp4', 'video/quicktime'])
            ->useDisk('public');

        // CV: File PDF, massimo 1 file
        $this->addMediaCollection('cv')
            ->acceptsMimeTypes(['application/pdf'])
            ->singleFile()
            ->useDisk('public');
    }

    // Cast automatico da JSON MariaDB ad Array PHP
    protected $casts = [
        'birth_date' => 'date',
        'appearance' => 'array',
        'measurements' => 'array',
        'capabilities' => 'array',
        'socials' => 'array',
        'is_visible' => 'boolean',
        'is_represented' => 'boolean',
        'consenso_privacy' => 'boolean',
    ];

    protected $fillable = [
        'user_id',
        'stage_name',
        'slug',
        'birth_date',
        'gender',
        'city',
        'country',
        'province',
        'phone',
        'height_cm',
        'weight_kg',
        'appearance',
        'measurements',
        'capabilities',
        'socials',
        'is_visible',
        'is_represented',
        'scene_nudo',
        'consenso_privacy',
    ];

    // Relazione con User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Accessor per calcolare l'età al volo
    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->birth_date)->age;
    }

    // Accessor per ottenere l'URL di WhatsApp
    public function getWhatsappUrlAttribute(): ?string
    {
        if (empty($this->phone)) {
            return null;
        }

        // Rimuovi spazi e caratteri speciali dal numero
        $phone = preg_replace('/[^0-9]/', '', $this->phone);

        // Aggiungi il prefisso internazionale se mancante
        if (!str_starts_with($phone, '+')) {
            $phone = '39' . ltrim($phone, '0'); // 39 è il prefisso italiano
        }

        return 'https://wa.me/' . $phone;
    }
}
