<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relazione con User (opzionale)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper per ottenere il tipo di servizio formattato
    public function getServiceTypeLabelAttribute(): string
    {
        return match($this->service_type) {
            'catering' => 'Catering',
            'hair' => 'Parrucchiere',
            'makeup' => 'Truccatrice',
            'costume' => 'Sartoria/Costumi',
            'location' => 'Location',
            'equipment' => 'Attrezzature',
            'transport' => 'Trasporti',
            'security' => 'Sicurezza',
            'photography' => 'Fotografia',
            'video' => 'Video',
            'sound' => 'Audio',
            'other' => 'Altro',
            default => ucfirst($this->service_type),
        };
    }

    // Scope per servizi attivi
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope per tipo di servizio
    public function scopeOfType($query, string $type)
    {
        return $query->where('service_type', $type);
    }
}
