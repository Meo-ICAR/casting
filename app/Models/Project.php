<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }



    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    /**
     * Get all project services for this project.
     */
    public function projectServices(): HasMany
    {
        return $this->hasMany(ProjectService::class);
    }

    /**
     * Get all project locations for this project.
     */
    public function projectLocations(): HasMany
    {
        return $this->hasMany(ProjectLocation::class);
    }

    /**
     * Get all quotations for this project.
     */
    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class);
    }
}
