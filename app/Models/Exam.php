<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = [
        'title', 'exam_type', 'batch_id', 'subject', 'date', 'time',
        'total_marks', 'pass_marks', 'room', 'status'
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }
}
