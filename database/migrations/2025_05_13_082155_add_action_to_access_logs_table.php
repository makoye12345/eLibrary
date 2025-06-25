<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('access_logs', function (Blueprint $table) {
            // Add 'action' column with default value
            $table->string('action')->default('accessed'); // Default value 'accessed'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('access_logs', function (Blueprint $table) {
            // Drop 'action' column during rollback
            $table->dropColumn('action');
        });
    }
};
