<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\ApplicationStatus; // Assicurati di aver creato l'Enum
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    protected $guarded = [];

    protected $casts = [
        // Mappa la stringa del DB (es. 'pending') direttamente nell'oggetto Enum PHP
        'status' => ApplicationStatus::class,
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }
    /**
     * Get the human-readable status label
     */
    public function getStatusLabel(): string
    {
        return $this->status->getLabel() ?? $this->status->value;
    }
    /**
     * Get the color for the status badge
     */
    public function getStatusColor(): string
    {
        return match($this->status->value) {
            'pending' => 'warning',
            'accepted' => 'success',
            'rejected' => 'danger',
            default => 'gray',
        };
    }
    /**
     * Check if application has a specific status
     */
    public function hasStatus(string $status): bool
    {
        return $this->status->value === $status;
    }
    /**
     * Scope to filter by status
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }


}
