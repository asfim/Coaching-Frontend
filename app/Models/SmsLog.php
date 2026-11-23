<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $fillable = [
        'recipient_type', 'recipient', 'phone', 'message', 'sent_at', 'status'
    ];
}
