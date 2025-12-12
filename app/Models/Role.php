<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    protected $guarded = [];

    protected $casts = [
        'requirements' => 'array', // JSON: {"gender": "female", "age_range": [20, 30]}
        'is_open'      => 'boolean',
        'start_date'   => 'date',
        'end_date'     => 'date',
        'scene_nudo'   => 'string',
        'n'            => 'integer',
    ];

    protected $fillable = [
        'project_id',
        'name',
        'description',
        'requirements',
        'salary_min',
        'salary_max',
        'is_open',
        'start_date',
        'end_date',
        'city',
        'scene_nudo',
        'n',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    // Scorciatoia per ottenere tutti gli attori candidati a questo ruolo
    public function candidates(): BelongsToMany
    {
        return $this->belongsToMany(Profile::class, 'applications')
                    ->withPivot(['status', 'director_notes'])
                    ->withTimestamps();
    }
}
