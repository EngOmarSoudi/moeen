<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function canAccessPanel(Panel $panel): bool
    {
        return true; // For now allow all users, or restrict to specific roles later
    }

    /**
     * Get the driver record associated with this user.
     */
    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class);
    }

    /**
     * Get the user's role type (driver, admin, or agent).
     */
    public function getUserType(): ?string
    {
        // Check if user is a driver
        if ($this->driver()->exists()) {
            return 'driver';
        }

        // Check if user has agent role
        if ($this->hasRole('agent')) {
            return 'agent';
        }

        // Check if user has admin role
        if ($this->hasRole('admin')) {
            return 'admin';
        }

        return null;
    }

    /**
     * Get the agent record associated with this user (via email).
     */
    public function getAgentAttribute(): ?Agent
    {
        if ($this->hasRole('agent')) {
            return Agent::where('email', $this->email)->first();
        }
        return null;
    }
}
