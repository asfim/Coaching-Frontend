<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Classes: Academic Class vs Target Batch
        Schema::table('classes', function (Blueprint $table) {
            $table->string('class_type')->default('Academic')->after('name');
        });

        // 2. Students: Academic Class vs Target Batch
        Schema::table('students', function (Blueprint $table) {
            $table->string('program_type')->default('Academic')->after('name');
        });

        // 3. Batches: Academic Class vs Target Batch
        Schema::table('batches', function (Blueprint $table) {
            $table->string('program_type')->default('Academic')->after('name');
        });

        // 4. Subjects: Academic Class vs Target Batch
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('program_type')->default('Academic')->after('name');
        });

        // 5. Exams: Academic Class vs Target Batch
        Schema::table('exams', function (Blueprint $table) {
            $table->string('program_type')->default('Academic')->after('title');
        });

        // 6. Results: Academic Class vs Target Batch + Merit Position
        Schema::table('results', function (Blueprint $table) {
            $table->string('program_type')->default('Academic')->after('grade');
            $table->integer('merit_position')->nullable()->after('gpa');
        });
    }

    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropColumn(['program_type', 'merit_position']);
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn('program_type');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn('program_type');
        });

        Schema::table('batches', function (Blueprint $table) {
            $table->dropColumn('program_type');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('program_type');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('class_type');
        });
    }
};
