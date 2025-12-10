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
}
