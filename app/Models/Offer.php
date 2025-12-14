<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offer extends Model
{
    protected $fillable = [
        'project_location_id',
        'location_id',
        'price',
        'notes',
        'status',
        'valid_until',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'valid_until' => 'date',
    ];

    public function projectLocation(): BelongsTo
    {
        return $this->belongsTo(ProjectLocation::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
