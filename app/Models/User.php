<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\UserRole;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;


class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;




    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'role',
        'company_id',
        'ip_address',
        'user_agent',
        'privacy_policy_accepted_at',
        'terms_accepted_at',
        'data_processing_consent_at',
        'data_erasure_requested_at',
        'data_anonymized_at',
        'marketing_consent',
        'newsletter_subscription',
        'data_processing_consent',
        'newsletter_subscription',

    ];

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
    return $this->hasRole('director') || $this->isAdmin();
}

    /**
     * Get all projects for the director's company
     */
    public function companyProjects()
    {
        if (!$this->company_id) {
            return collect();
        }

        return Project::where('company_id', $this->company_id)->get();
    }
public function isActor(): bool
{
    return $this->role === UserRole::ACTOR || $this->isAdmin();
}
public function isCasting(): bool
{
    return $this->role === UserRole::CASTING || $this->isAdmin();
}


// Replace the existing hasRole method with this:
public function hasRole($role): bool
{
    if (is_array($role)) {
        return in_array($this->role->value, $role);
    }
    return $this->role === UserRole::from($role);
}
// Also, update the canAccessPanel method to use the new hasRole method:
public function canAccessPanel(Panel $panel): bool
{
    return $this->hasRole('admin') || $this->hasRole('host') || $this->hasRole('servicer') || $this->hasRole('actor');
}
 public function hasAnyRole($roles): bool
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        return in_array($this->role->value, $roles);
    }

    // Update the getRoleNames method
    public function getRoleNames(): \Illuminate\Support\Collection
    {
        return collect([$this->role->value]);
    }

 // Update the getAllPermissions method
    public function getAllPermissions(): \Illuminate\Support\Collection
    {
        // If you have specific permissions per role, define them here
        $permissions = match($this->role) {
            UserRole::ADMIN => ['*'],
            UserRole::DIRECTOR => ['view_projects', 'manage_projects', 'view_services'],
            UserRole::SERVICER => ['view_services', 'manage_services'],
            UserRole::ACTOR => ['view_roles', 'apply_roles'],
            UserRole::HOST => ['host_services'],
            default => [],
        };
        return collect($permissions);
    }


    // Update the hasPermission method
    public function hasPermission($permission): bool
    {
        $permissions = $this->getAllPermissions();

        // If user has wildcard permission
        if ($permissions->contains('*')) {
            return true;
        }

        return $permissions->contains($permission);
    }

    // Update the hasAnyPermission method
    public function hasAnyPermission($permissions): bool
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }
        $userPermissions = $this->getAllPermissions();

        // If user has wildcard permission
        if ($userPermissions->contains('*')) {
            return true;
        }
        return $userPermissions->intersect($permissions)->isNotEmpty();
    }
   protected static function booted()
    {
        static::creating(function ($user) {
            // Set IP address and user agent during user creation
            $user->ip_address = request()->ip();
            $user->user_agent = request()->userAgent();

             // Set privacy-related timestamps
        $now = now();
        $user->privacy_policy_accepted_at = $now;
        $user->terms_accepted_at = $now;
        $user->data_processing_consent_at = $now;
        $user->data_erasure_requested_at = null; // Set to null by default
        $user->data_anonymized_at = null; // Set to null by default
        });

        static::created(function ($user) {
            // Assign default 'actor' role to new users
          //  $user->assignRole('actor');

            // Create profile for new user
            $user->profile()->create();
        });
    }

    // Add a method to assign role (if needed)
    public function assignRole($role): self
    {
        $this->role = UserRole::from($role);
        $this->save();
        return $this;
    }
    // Add a method to remove role (if needed)
    public function removeRole($role): self
    {
        if ($this->role === UserRole::from($role)) {
            $this->role = UserRole::ACTOR; // or whatever your default role is
            $this->save();
        }
        return $this;
    }
    // Add a method to sync roles (if needed)
    public function syncRoles($roles): self
    {
        if (!empty($roles)) {
            $this->role = UserRole::from(is_array($roles) ? $roles[0] : $roles);
            $this->save();
        }
        return $this;
    }


public function getPermissionNames(): \Illuminate\Support\Collection
{
    return $this->getAllPermissions();
}





}
