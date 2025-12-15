<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\UserRole;
use Spatie\Permission\Traits\HasRoles;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('admin') || $this->hasRole('host') || $this->hasRole('servicer');
    }


    protected $fillable = ['name', 'last_name', 'email', 'password', 'role', 'company_id'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',
        'role' => UserRole::class,
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


    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    // I preferiti del Director (Many-to-Many verso i Profili)
    public function shortlistedProfiles(): BelongsToMany
    {
        return $this->belongsToMany(Profile::class, 'shortlists')
                    ->withTimestamps();
    }

    // --- Helper ---

    // Add these helper methods
public function isAdmin(): bool
{
    return $this->role === UserRole::ADMIN;
}
public function isHost(): bool
{
    return $this->role === UserRole::HOST || $this->isAdmin();
}
public function isServicer(): bool
{
    return $this->role === UserRole::SERVICER || $this->isAdmin();
}
// Update existing role check methods to use the enum
public function isDirector(): bool
{
    return $this->role === UserRole::DIRECTOR || $this->isAdmin();
}
public function isActor(): bool
{
    return $this->role === UserRole::ACTOR || $this->isAdmin();
}
public function isCasting(): bool
{
    return $this->role === UserRole::CASTING || $this->isAdmin();
}


public function hasRole($role): bool
{
    return $this->roles->contains('name', $role);
}

public function hasAnyRole($roles): bool
{
    return $this->roles->pluck('name')->intersect($roles)->isNotEmpty();
}

public function getRoleNames(): \Illuminate\Support\Collection
{
    return $this->roles->pluck('name');
}

public function getAllPermissions(): \Illuminate\Support\Collection
{
    return $this->permissions->pluck('name');
}

public function getPermissionNames(): \Illuminate\Support\Collection
{
    return $this->getAllPermissions();
}

public function hasPermission($permission): bool
{
    return $this->getAllPermissions()->contains($permission);
}

public function hasAnyPermission($permissions): bool
{
    return $this->getAllPermissions()->intersect($permissions)->isNotEmpty();
}


protected static function booted()
    {
        static::created(function ($user) {
            // Assign default 'actor' role to new users
            $user->assignRole('actor');

            // Create profile for new user
            $user->profile()->create();
        });
    }
}
