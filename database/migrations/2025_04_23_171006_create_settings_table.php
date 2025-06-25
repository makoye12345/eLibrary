<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_create_settings_table.php

public function up()
{
    Schema::create('settings', function (Blueprint $table) {
        $table->id();
        $table->string('library_name')->nullable();
        $table->string('contact_email')->nullable();
        $table->integer('borrow_limit')->default(5);
        $table->integer('return_days')->default(14);
        $table->integer('late_fine')->default(500);
        $table->boolean('email_reminders')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }


    
};
