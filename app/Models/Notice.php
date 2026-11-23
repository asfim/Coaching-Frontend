<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = [
        'title', 'category', 'content', 'published_at', 'target_audience', 'status'
    ];
}
