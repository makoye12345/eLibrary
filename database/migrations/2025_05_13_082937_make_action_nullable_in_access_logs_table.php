<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeActionNullableInAccessLogsTable extends Migration
{
    public function up()
    {
        Schema::table('access_logs', function (Blueprint $table) {
            $table->string('action')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('access_logs', function (Blueprint $table) {
            $table->string('action')->nullable(false)->change();
        });
    }
}