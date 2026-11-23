<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'designation', 'subject', 'qualification',
        'salary', 'join_date', 'image', 'bio', 'facebook', 'linkedin', 'status'
    ];

    public function routines(): HasMany
    {
        return $this->hasMany(Routine::class);
    }
}
