<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

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
        // Admin and agent roles can access admin panel
        if ($panel->getId() === 'admin') {
            return $this->hasRole(['super_admin', 'admin', 'agent']);
        }

        // Regular users can access user panel
        if ($panel->getId() === 'user') {
            return $this->hasRole(['user', 'super_admin', 'admin', 'agent']);
        }

        return false;
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_users')
            ->withTimestamps();
    }

    public function managedDepartments()
    {
        return $this->hasMany(Department::class, 'manager_id');
    }
}
