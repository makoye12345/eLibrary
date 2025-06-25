<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToBorrows extends Migration
{
    public function up()
    {
        Schema::table('borrows', function (Blueprint $table) {
            $table->foreignId('book_id')->nullable()->change();
            $table->foreign('book_id')->references('id')->on('books')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('borrows', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
            $table->foreignId('book_id')->change();
        });
    }
}