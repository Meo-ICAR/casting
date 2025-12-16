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
  /**
     * Register the media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photos')
            ->useDisk('public')
            ->singleFile(false)
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif']);
    }

    /**
     * Register the conversions for media files.
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('photos');

        $this->addMediaConversion('preview')
            ->width(800)
            ->height(600)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('photos');
    }

    /**
     * Get all the photos for the location.
     */
    public function getPhotosAttribute()
    {
        return $this->getMedia('photos');
    }

    /**
     * Get the primary photo for the location.
     */
    public function getPrimaryPhotoAttribute()
    {
        return $this->getFirstMedia('photos');
    }



    /**
     * Get the main photo URL.
     */
    public function getMainPhotoUrlAttribute(): ?string
    {
        return $this->primary_photo?->getUrl('preview');
    }

    /**
     * Get the thumbnail URL.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->primary_photo?->getUrl('thumb');
    }

}
