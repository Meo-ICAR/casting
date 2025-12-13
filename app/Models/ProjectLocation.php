<?php

namespace App\Models;

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
    ];

    protected $casts = [
        'shooting_date' => 'date',
        'permission_required' => 'boolean',
        'specifications' => 'array',
    ];

    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_REQUESTED = 'requested';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    // Location type constants
    public const TYPE_INTERNAL = 'internal';
    public const TYPE_EXTERNAL = 'external';
    public const TYPE_PUBLIC = 'public';
    public const TYPE_PRIVATE = 'private';
    public const TYPE_OTHER = 'other';

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
        return [
            self::STATUS_PENDING => 'In attesa',
            self::STATUS_REQUESTED => 'Richiesto',
            self::STATUS_CONFIRMED => 'Confermato',
            self::STATUS_COMPLETED => 'Completato',
            self::STATUS_CANCELLED => 'Annullato',
        ];
    }

    /**
     * Get the location type options.
     */
    public static function getLocationTypeOptions(): array
    {
        return [
            self::TYPE_INTERNAL => 'Interna',
            self::TYPE_EXTERNAL => 'Esterna',
            self::TYPE_PUBLIC => 'Pubblica',
            self::TYPE_PRIVATE => 'Privata',
            self::TYPE_OTHER => 'Altro',
        ];
    }
}
