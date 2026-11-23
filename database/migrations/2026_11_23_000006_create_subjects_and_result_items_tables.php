<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Subjects table
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->text('description')->nullable();
            $table->string('status')->default('Active');
            $table->timestamps();
        });

        // 2. Add subject_id to exams table
        Schema::table('exams', function (Blueprint $table) {
            $table->foreignId('subject_id')->nullable()->after('subject')->constrained('subjects')->nullOnDelete();
        });

        // 3. Result Items table (Subject-wise breakdown for results)
        Schema::create('result_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('result_id')->constrained('results')->cascadeOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->string('subject_name');
            $table->decimal('obtained_marks', 6, 2)->default(0);
            $table->decimal('total_marks', 6, 2)->default(100);
            $table->decimal('gpa', 3, 2)->default(0);
            $table->string('grade', 5)->default('F');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('result_items');

        Schema::table('exams', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropColumn('subject_id');
        });

        Schema::dropIfExists('subjects');
    }
};
