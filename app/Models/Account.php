<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'title', 'type', 'category', 'amount', 'payment_method',
        'date', 'reference', 'description'
    ];

    // Scopes for easy queries
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }
}
