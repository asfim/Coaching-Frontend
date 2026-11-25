<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->string('fee_type')->default('onetime')->after('batch_id');
            $table->integer('installment_no')->nullable()->after('fee_type');
            $table->integer('total_installments')->nullable()->after('installment_no');
            $table->date('due_date')->nullable()->after('payment_date');
        });
    }

    public function down(): void
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->dropColumn(['fee_type', 'installment_no', 'total_installments', 'due_date']);
        });
    }
};
