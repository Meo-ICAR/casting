<?php

namespace App\Models;

use App\Enums\ProjectLocationStatus;
use App\Enums\ProjectLocationType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'description',
        'location_type',
        'address',
        'city',
        'province',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'shooting_date',
        'shooting_time_from',
        'shooting_time_to',
        'status',
        'permission_required',
        'permission_details',
        'notes',
        'specifications',
         'is_open',
    ];

    protected $casts = [
        'shooting_date' => 'date',
        'permission_required' => 'boolean',
        'specifications' => 'array',
        'status' => ProjectLocationStatus::class,
        'location_type' => ProjectLocationType::class,
    ];

    // Status constants (kept for backward compatibility)
    public const STATUS_PENDING = ProjectLocationStatus::PENDING->value;
    public const STATUS_REQUESTED = ProjectLocationStatus::REQUESTED->value;
    public const STATUS_CONFIRMED = ProjectLocationStatus::CONFIRMED->value;
    public const STATUS_COMPLETED = ProjectLocationStatus::COMPLETED->value;
    public const STATUS_CANCELLED = ProjectLocationStatus::CANCELLED->value;

    // Location type constants (kept for backward compatibility)
    public const TYPE_INTERNAL = ProjectLocationType::INTERNAL->value;
    public const TYPE_EXTERNAL = ProjectLocationType::EXTERNAL->value;
    public const TYPE_PUBLIC = ProjectLocationType::PUBLIC->value;
    public const TYPE_PRIVATE = ProjectLocationType::PRIVATE->value;
    public const TYPE_OTHER = ProjectLocationType::OTHER->value;

    /**
     * Get the project that owns the location.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the status options for the location.
     */
    public static function getStatusOptions(): array
    {
        return ProjectLocationStatus::options();
    }

    /**
     * Get the location type options.
     */
    public static function getLocationTypeOptions(): array
    {
        return ProjectLocationType::options();
    }

    /**
     * Get the color for the current status.
     */
    public function getStatusColorAttribute(): string
    {
        return $this->status?->getColor() ?? 'gray';
    }

    /**
     * Get the label for the current status.
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status?->getLabel() ?? 'Sconosciuto';
    }

    /**
     * Get the label for the current location type.
     */
    public function getLocationTypeLabelAttribute(): string
    {
        return $this->location_type?->getLabel() ?? 'Non specificato';
    }
}
