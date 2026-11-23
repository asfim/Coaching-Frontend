<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Drop existing global unique index on code
            $table->dropUnique(['code']);
            // Add compound index for code and class_id
            $table->index(['class_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropIndex(['class_id', 'code']);
            $table->unique('code');
        });
    }
};
