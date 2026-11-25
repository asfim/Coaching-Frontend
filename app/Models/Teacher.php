<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Teacher extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'designation', 'subject', 'qualification',
        'salary', 'join_date', 'image', 'bio', 'facebook', 'linkedin', 'status'
    ];

    protected $appends = ['permissions_list'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function getPermissionsAttribute(): array
    {
        $user = $this->relationLoaded('user') ? $this->user : $this->user()->first();
        if (!$user) {
            return [];
        }
        return $user->getPermissionNames()->values()->toArray();
    }

    public function getPermissionsListAttribute(): array
    {
        return $this->permissions;
    }

    public function routines(): HasMany
    {
        return $this->hasMany(Routine::class);
    }
}
