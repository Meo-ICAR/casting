<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasWhatsapp;

class Service extends Model
{
    use HasFactory, SoftDeletes, HasWhatsapp;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = ['service_type_label'];

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class);
    }

    public function getServiceTypeLabelAttribute(): string
    {
        if ($this->relationLoaded('serviceType') && $this->serviceType) {
            return $this->serviceType->name;
        }

        return $this->service_type
            ? ucfirst($this->service_type)
            : 'Non specificato';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    public function scopeOfType($query, string $type)
    {
        return $query->where('service_type', $type);
    }
}
