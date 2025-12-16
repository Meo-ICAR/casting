<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use Spatie\Image\Manipulations;
use App\Traits\HasWhatsapp; // <--- Importa il Trait
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Location extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;
    use HasWhatsapp; // <--- Attivalo qui

    /**
     * Get all offers for this location.
     */
    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'address',
        'city',
        'province',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'contact_person',
        'contact_phone',
        'contact_email',
        'features',
        'notes',
        'is_active',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * Get the user who created the location.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to only include active locations.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the full address as a single string.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = [
            $this->address,
            $this->postal_code,
            $this->city,
            $this->province,
            $this->country,
        ];

        return implode(', ', array_filter($parts));
    }

    /**
     * Check if the location has coordinates.
     */
    public function hasCoordinates(): bool
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
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
