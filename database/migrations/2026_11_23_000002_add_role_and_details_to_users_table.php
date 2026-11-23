<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 30)->default('student')->after('email'); // admin, teacher, student, parent
            $table->string('phone', 25)->nullable()->after('role');
            $table->string('avatar')->nullable()->after('phone');
            $table->foreignId('teacher_id')->nullable()->after('avatar')->constrained('teachers')->nullOnDelete();
            $table->foreignId('student_id')->nullable()->after('teacher_id')->constrained('students')->nullOnDelete();
            $table->string('status', 20)->default('active')->after('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropForeign(['student_id']);
            $table->dropColumn(['role', 'phone', 'avatar', 'teacher_id', 'student_id', 'status']);
        });
    }
};
