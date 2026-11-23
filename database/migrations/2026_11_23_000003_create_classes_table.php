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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g. Class IX, Class X, Class XI, HSC 2nd Year, Admission
            $table->string('code')->nullable(); // e.g. C09, C10, C11, C12, ADM
            $table->integer('numeric_level')->nullable(); // e.g. 9, 10, 11, 12, 13
            $table->text('description')->nullable();
            $table->string('status', 20)->default('Active'); // Active, Inactive
            $table->timestamps();
        });

        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('class_id')->nullable()->after('class_name')->constrained('classes')->nullOnDelete();
        });

        Schema::table('batches', function (Blueprint $table) {
            $table->foreignId('class_id')->nullable()->after('class_name')->constrained('classes')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropColumn('class_id');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropColumn('class_id');
        });

        Schema::dropIfExists('classes');
    }
};
