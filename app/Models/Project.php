<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Project extends Model implements HasMedia


{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }


public function setPosterAttribute($value)
{
    if ($value) {
        if ($this->hasMedia('poster')) {
            $this->clearMediaCollection('poster');
        }
        $this->addMedia($value)
             ->toMediaCollection('poster');
    }
}

// Add this method to get the poster URL
public function getPosterUrlAttribute()
{
    return $this->getFirstMediaUrl('poster');
}
public function registerMediaCollections(): void
    {
        $this->addMediaCollection('poster')
             ->singleFile()
             ->useDisk('public')
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
             ->withResponsiveImages();
             $this->addMediaConversion('thumb')
        ->width(200)
        ->height(300)
        ->sharpen(10)
        ->nonQueued();
    $this->addMediaConversion('preview')
        ->width(400)
        ->height(600)
        ->sharpen(10)
        ->nonQueued();
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
