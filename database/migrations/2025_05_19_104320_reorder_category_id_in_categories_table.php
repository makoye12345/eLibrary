<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReorderCategoryIdInCategoriesTable extends Migration
{
    public function up()
    {
        // Drop existing category_id column
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        // Add category_id after id
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('categories')
                  ->onDelete('set null')
                  ->after('id');
        });
    }

    public function down()
    {
        // Revert to original position (after description)
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('categories')
                  ->onDelete('set null')
                  ->after('description');
        });
    }
}