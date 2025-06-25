<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            // Fill existing null descriptions with a default value
            \App\Models\Category::whereNull('description')->update(['description' => 'Default description']);
            // Make description non-nullable
            $table->text('description')->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });
    }
};