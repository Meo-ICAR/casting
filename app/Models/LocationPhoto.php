<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class LocationPhoto extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location_id',
        'path',
        'caption',
        'order',
        'is_primary',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_primary' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saving(function ($photo) {
            // If this photo is set as primary, unset primary from other photos
            if ($photo->is_primary) {
                $photo->location->photos()
                    ->where('id', '!=', $photo->id)
                    ->update(['is_primary' => false]);

                // Update the main_photo_path on the location
                $photo->location->update(['main_photo_path' => $photo->path]);
            }
        });

        static::deleting(function ($photo) {
            // If the photo being deleted is primary, set another photo as primary if available
            if ($photo->is_primary) {
                $newPrimary = $photo->location->photos()
                    ->where('id', '!=', $photo->id)
                    ->orderBy('order')
                    ->first();

                if ($newPrimary) {
                    $newPrimary->update(['is_primary' => true]);
                } else {
                    // No more photos, clear the main_photo_path
                    $photo->location->update(['main_photo_path' => null]);
                }
            }
        });
    }

    /**
     * Get the location that owns the photo.
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the URL to the photo.
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }

    /**
     * Get the full path to the photo.
     *
     * @return string
     */
    public function getFullPathAttribute(): string
    {
        return Storage::path($this->path);
    }

    /**
     * Scope a query to only include primary photos.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}
