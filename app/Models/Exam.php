<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = [
        'title', 'program_type', 'exam_type', 'exam_type_id', 'batch_id', 'class_id', 'subject', 'subject_id', 'date', 'time',
        'total_marks', 'pass_marks', 'room', 'status'
    ];

    public function academicClass(): BelongsTo
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function subjectModel(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function typeModel(): BelongsTo
    {
        return $this->belongsTo(ExamType::class, 'exam_type_id');
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }
}
