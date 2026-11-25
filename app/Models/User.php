<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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
        'role',
        'phone',
        'avatar',
        'teacher_id',
        'student_id',
        'status',
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

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isParent(): bool
    {
        return $this->role === 'parent';
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        try {
            if ($this->hasRole('admin') || $this->hasPermissionTo($permission) || $this->can($permission)) {
                return true;
            }
        } catch (\Throwable $e) {}

        return false;
    }

    public function canEdit(?string $module = null): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->hasPermission('can_edit')) {
            return true;
        }

        if ($module) {
            return $this->hasPermission("{$module}.edit") || $this->hasPermission("edit_{$module}");
        }

        return false;
    }

    public function canDelete(?string $module = null): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->hasPermission('can_delete')) {
            return true;
        }

        if ($module) {
            return $this->hasPermission("{$module}.delete") || $this->hasPermission("delete_{$module}");
        }

        return false;
    }
}
