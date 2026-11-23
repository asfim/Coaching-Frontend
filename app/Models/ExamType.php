<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ExamType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($examType) {
            if (empty($examType->slug)) {
                $examType->slug = Str::slug($examType->name);
            }
        });
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'exam_type_id');
    }
}
