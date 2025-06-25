<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('borrows', function (Blueprint $table) {
        $table->date('due_date')->nullable(); // Adjust the column type as needed
    });
}

public function down()
{
    Schema::table('borrows', function (Blueprint $table) {
        $table->dropColumn('due_date');
    });
}

};
