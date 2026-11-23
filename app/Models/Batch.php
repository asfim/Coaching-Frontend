<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    protected $fillable = [
        'course_id', 'name', 'batch_code', 'class_name', 'days', 'time_slot', 'room', 'max_students', 'status'
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function routines(): HasMany
    {
        return $this->hasMany(Routine::class);
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }
}
