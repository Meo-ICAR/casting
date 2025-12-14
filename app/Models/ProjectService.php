<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ProjectService extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'service_type_id',
        'name',
        'description',
        'city',
        'quantity',
        'unit',
        'estimated_cost',
        'status',
        'needed_from',
        'needed_until',
        'notes',
        'specifications',
         'is_open',
    ];

    /**
     * Get the service type that owns the project service.
     */
    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    protected $casts = [
        'quantity' => 'integer',
        'estimated_cost' => 'decimal:2',
        'needed_from' => 'date',
        'needed_until' => 'date',
        'specifications' => 'array',
    ];

    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_REQUESTED = 'requested';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    // Service type relationship will be handled by the ServiceType model

    // Unit options
    public const UNIT_HOUR = 'hour';
    public const UNIT_DAY = 'day';
    public const UNIT_WEEK = 'week';
    public const UNIT_PIECE = 'piece';
    public const UNIT_PERSON = 'person';
    public const UNIT_EVENT = 'event';

    /**
     * Get the project that owns the service requirement.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the status options for the service.
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
     * Get the service type options.
     */
    public static function getServiceTypeOptions(): array
    {
        return [
            self::TYPE_CATERING => 'Ristorazione',
            self::TYPE_EQUIPMENT => 'Attrezzatura',
            self::TYPE_CREW => 'Personale',
            self::TYPE_VENUE => 'Location',
            self::TYPE_TRANSPORT => 'Trasporto',
            self::TYPE_OTHER => 'Altro',
        ];
    }

    /**
     * Get the unit options.
     */
    public static function getUnitOptions(): array
    {
        return [
            self::UNIT_HOUR => 'Ora',
            self::UNIT_DAY => 'Giorno',
            self::UNIT_WEEK => 'Settimana',
            self::UNIT_PIECE => 'Pezzo',
            self::UNIT_PERSON => 'Persona',
            self::UNIT_EVENT => 'Evento',
        ];
    }

    /**
     * Get the formatted estimated cost.
     */
    protected function formattedEstimatedCost(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if (empty($attributes['estimated_cost'])) {
                    return null;
                }
                return '€ ' . number_format($attributes['estimated_cost'], 2, ',', '.');
            }
        );
    }

    /**
     * Get the display name for the service.
     */
    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $name = $attributes['name'];
                if (!empty($attributes['quantity']) && !empty($attributes['unit'])) {
                    $unit = $this->getUnitOptions()[$attributes['unit']] ?? $attributes['unit'];
                    $name = "{$attributes['quantity']} {$unit} - {$name}";
                }
                return $name;
            }
        );
    }

    /**
     * Scope a query to only include services of a given status.
     */
    public function scopeOfStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include services of a given type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('service_type', $type);
    }

    public function quotations()
{
    return $this->hasMany(\App\Models\Quotation::class, 'project_service_id');
}
}
