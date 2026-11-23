<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Courses
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->string('category')->default('Science');
            $table->string('duration')->default('12 Months');
            $table->integer('seats')->default(50);
            $table->decimal('fee', 10, 2)->default(2500.00);
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->default('Active');
            $table->timestamps();
        });

        // 2. Batches
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->string('name');
            $table->string('batch_code');
            $table->string('class_name')->default('Class X');
            $table->string('days')->default('Sun, Tue, Thu');
            $table->string('time_slot')->default('09:00 AM - 11:00 AM');
            $table->string('room')->default('Room 201');
            $table->integer('max_students')->default(40);
            $table->string('status')->default('Active');
            $table->timestamps();
        });

        // 3. Teachers
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('designation')->nullable();
            $table->string('subject');
            $table->string('qualification')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->date('join_date')->nullable();
            $table->string('image')->nullable();
            $table->text('bio')->nullable();
            $table->string('facebook')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('status')->default('Active');
            $table->timestamps();
        });

        // 4. Students
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('guardian_name')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('class_name')->default('Class X');
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
            $table->date('admission_date')->nullable();
            $table->string('blood_group')->nullable();
            $table->text('address')->nullable();
            $table->string('avatar')->nullable();
            $table->string('status')->default('Active');
            $table->timestamps();
        });

        // 5. Attendance
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late', 'leave'])->default('present');
            $table->string('remarks')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'date']);
        });

        // 6. Exams
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('exam_type')->default('Model Test');
            $table->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
            $table->string('subject');
            $table->date('date');
            $table->string('time')->default('09:00 AM - 12:00 PM');
            $table->integer('total_marks')->default(100);
            $table->integer('pass_marks')->default(40);
            $table->string('room')->nullable();
            $table->string('status')->default('Upcoming');
            $table->timestamps();
        });

        // 7. Results
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->decimal('obtained_marks', 5, 2)->default(0);
            $table->decimal('total_marks', 5, 2)->default(100);
            $table->decimal('gpa', 3, 2)->default(5.00);
            $table->string('grade')->default('A+');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // 8. Routines
        Schema::create('routines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('batches')->cascadeOnDelete();
            $table->string('day')->default('Sunday');
            $table->string('subject');
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->string('start_time')->default('09:00 AM');
            $table->string('end_time')->default('10:30 AM');
            $table->string('room')->nullable();
            $table->timestamps();
        });

        // 9. Fees
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
            $table->string('month')->default('October');
            $table->integer('year')->default(2026);
            $table->decimal('amount', 10, 2)->default(2500.00);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('paid_amount', 10, 2)->default(2500.00);
            $table->decimal('due_amount', 10, 2)->default(0.00);
            $table->string('payment_method')->default('Cash');
            $table->date('payment_date')->nullable();
            $table->string('status')->default('Paid');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 10. Accounts (Income, Expense, Transactions)
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['income', 'expense'])->default('income');
            $table->string('category')->default('Student Fee');
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->string('payment_method')->default('Cash');
            $table->date('date');
            $table->string('reference')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 11. Notices
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('general');
            $table->text('content');
            $table->date('published_at');
            $table->string('target_audience')->default('all');
            $table->string('status')->default('published');
            $table->timestamps();
        });

        // 12. SMS Logs
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_type')->default('All Students');
            $table->string('recipient')->nullable();
            $table->string('phone');
            $table->text('message');
            $table->timestamp('sent_at')->useCurrent();
            $table->string('status')->default('Delivered');
            $table->timestamps();
        });

        // 13. Settings
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('sms_logs');
        Schema::dropIfExists('notices');
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('fees');
        Schema::dropIfExists('routines');
        Schema::dropIfExists('results');
        Schema::dropIfExists('exams');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('students');
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('batches');
        Schema::dropIfExists('courses');
    }
};
