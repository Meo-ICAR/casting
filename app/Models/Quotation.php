<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Service;
use App\Models\ProjectService;

class Quotation extends Model
{
    use HasFactory, SoftDeletes;

    // Status constants
    public const STATUS_PROPOSAL = 'proposta';
    public const STATUS_REVIEW = 'in_esame';
    public const STATUS_NEGOTIATION = 'contrattazione';
    public const STATUS_ACCEPTED = 'accettato';
    public const STATUS_REJECTED = 'scartato';

    protected $fillable = [
        'project_service_id',
        'status',
        'notes',
        'proposed_price',
        'final_price',
        'valid_until',
        'rejection_reason',
    ];

    protected $casts = [
        'proposed_price' => 'decimal:2',
        'final_price' => 'decimal:2',
        'valid_until' => 'date',
    ];

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PROPOSAL => 'Proposta',
            self::STATUS_REVIEW => 'In Esame',
            self::STATUS_NEGOTIATION => 'In Contrattazione',
            self::STATUS_ACCEPTED => 'Accettato',
            self::STATUS_REJECTED => 'Scartato',
        ];
    }

    /**
     * Get the project service that owns the quotation.
     */
    public function projectService()
    {
        return $this->belongsTo(ProjectService::class);
    }

    /**
     * Get the project that owns the project service.
     */
    public function project()
    {
        return $this->hasOneThrough(
            Project::class,
            ProjectService::class,
            'id', // Foreign key on project_services table
            'id', // Foreign key on projects table
            'project_service_id', // Local key on quotations table
            'project_id' // Local key on project_services table
        );
    }

    /**
     * Get the service type through project service.
     */
    public function service()
    {
        /*
        return $this->hasOneThrough(
            Service::class,
            ProjectService::class,
            'id', // Foreign key on project_services table
            'id', // Foreign key on services table
            'project_service_id', // Local key on quotations table
            'service_type_id' // Local key on project_services table
            );
        */
             return $this->belongsTo(Service::class);
        //
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACCEPTED => 'success',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_NEGOTIATION => 'warning',
            self::STATUS_REVIEW => 'info',
            default => 'gray',
        };
    }
    // Add to the relationships in the Quotation model

}
