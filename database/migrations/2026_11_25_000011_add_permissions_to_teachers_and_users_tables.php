<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->text('permissions')->nullable()->after('status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->text('permissions')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('permissions');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('permissions');
        });
    }
};
