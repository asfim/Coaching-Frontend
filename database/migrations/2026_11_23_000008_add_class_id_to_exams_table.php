<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Exam;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->foreignId('class_id')->nullable()->after('batch_id')->constrained('classes')->nullOnDelete();
        });

        // Backfill existing exams class_id from their batches
        $exams = Exam::with('batch')->get();
        foreach ($exams as $exam) {
            if ($exam->batch && $exam->batch->class_id) {
                $exam->update(['class_id' => $exam->batch->class_id]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropColumn('class_id');
        });
    }
};
