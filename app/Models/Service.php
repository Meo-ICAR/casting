<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasWhatsapp; // <--- Importa il Trait

class Service extends Model
{
    use HasFactory;
    use HasWhatsapp; // <--- Attivalo qui

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    // Relazione con User (opzionale)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper per ottenere il tipo di servizio formattato
    public function getServiceTypeLabelAttribute(): string
    {
        if ($this->relationLoaded('serviceType') && $this->serviceType) {
            return $this->serviceType->name;
        }

        return $this->service_type
            ? ucfirst($this->service_type)
            : 'Non specificato';
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
