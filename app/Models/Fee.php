<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fee extends Model
{
    protected $fillable = [
        'invoice_no', 'student_id', 'batch_id', 'fee_type', 'installment_no',
        'total_installments', 'month', 'year', 'amount', 'discount',
        'paid_amount', 'due_amount', 'payment_method', 'payment_date',
        'due_date', 'status', 'notes'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }
}
