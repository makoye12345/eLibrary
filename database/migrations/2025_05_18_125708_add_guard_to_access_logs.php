<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGuardToAccessLogs extends Migration
{
    public function up()
    {
        Schema::table('access_logs', function (Blueprint $table) {
            $table->string('guard', 20)->nullable()->after('user_id'); // Ongeza guard column
        });
    }

    public function down()
    {
        Schema::table('access_logs', function (Blueprint $table) {
            $table->dropColumn('guard');
        });
    }
}