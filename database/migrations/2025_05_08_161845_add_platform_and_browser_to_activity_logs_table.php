<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            // Add platform column if not exists
            if (!Schema::hasColumn('activity_logs', 'platform')) {
                $table->string('platform')->nullable()->after('ip_address');
            }
            // Add browser column if not exists
            if (!Schema::hasColumn('activity_logs', 'browser')) {
                $table->string('browser')->nullable()->after('platform');
            }
        });
    }

    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn(['platform', 'browser']);
        });
    }
};