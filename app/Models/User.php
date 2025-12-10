<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable // implements FilamentUser (se vuoi restringere l'accesso)
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'last_name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',
    ];

    // --- Relazioni ---

    // Un utente (Attore) ha un solo Profilo
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    // Un utente (Director) crea molti Progetti
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    // I preferiti del Director (Many-to-Many verso i Profili)
    public function shortlistedProfiles(): BelongsToMany
    {
        return $this->belongsToMany(Profile::class, 'shortlists')
                    ->withTimestamps();
    }

    // --- Helper ---

    public function isDirector(): bool
    {
        return $this->role === 'director' || $this->role === 'admin';
    }
}
