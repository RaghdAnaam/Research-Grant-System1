<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'academician_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => UserRole::class,
    ];

    public function academician()
    {
        return $this->belongsTo(Academician::class);
    }

    public function grantsAsLeader()
    {
        return $this->academician
            ? $this->academician->grantsAsLeader()
            : Grant::query()->whereRaw('1 = 0');
    }

    public function grantsAsMember()
    {
        return $this->academician
            ? $this->academician->grantsAsMember()
            : Grant::query()->whereRaw('1 = 0');
    }

    public function hasRole(UserRole|string $role): bool
    {
        return $this->role?->matches($role) ?? false;
    }

    public function hasAnyRole(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    public function dashboardRouteName(): string
    {
        return $this->role?->dashboardRouteName() ?? 'dashboard';
    }
}
