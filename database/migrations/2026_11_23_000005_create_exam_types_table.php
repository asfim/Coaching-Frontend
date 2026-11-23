<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('status')->default('Active');
            $table->timestamps();
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->foreignId('exam_type_id')->nullable()->after('exam_type')->constrained('exam_types')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropForeign(['exam_type_id']);
            $table->dropColumn('exam_type_id');
        });

        Schema::dropIfExists('exam_types');
    }
};
