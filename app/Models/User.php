<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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

    /**
     * Check if user is an Administrator (full CRUD & management access)
     */
    public function isAdmin(): bool
    {
        return strtoupper($this->role ?? 'ADMIN') === 'ADMIN';
    }

    /**
     * Check if user is a Viewer / Read-Only user
     */
    public function isViewer(): bool
    {
        return strtoupper($this->role ?? '') === 'VIEWER';
    }

    /**
     * Human-readable role label
     */
    public function getRoleBadgeLabel(): string
    {
        return $this->isAdmin() ? 'Administrator' : 'Pengamat (Viewer)';
    }
}
