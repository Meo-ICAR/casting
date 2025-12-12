<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Role;
use App\Models\ProjectService;
use App\Models\ProjectLocation;

class Project extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    // Add these methods to the Project model
/**
 * Get all project services for this project.
 */
public function projectServices()
{
    return $this->hasMany(ProjectService::class);
}
/**
 * Get all project locations for this project.
 */
public function projectLocations()
{
    return $this->hasMany(ProjectLocation::class);
}
}
