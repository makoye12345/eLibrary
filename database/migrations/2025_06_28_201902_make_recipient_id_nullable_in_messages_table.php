<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeRecipientIdNullableInMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            // Badilisha recipient_id kuwa nullable
            $table->foreignId('recipient_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            // Rejesha recipient_id kuwa not nullable (kama ilivyokuwa awali)
            // Kuwa makini na data iliyopo ikiwa utarudisha nyuma
            $table->foreignId('recipient_id')->nullable(false)->change();
        });
    }
}