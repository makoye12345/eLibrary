<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMessagesTable extends Migration
{
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'content')) {
                $table->renameColumn('content', 'message');
            }
            if (!Schema::hasColumn('messages', 'name')) {
                $table->string('name')->nullable()->after('recipient_id');
            }
            if (!Schema::hasColumn('messages', 'email')) {
                $table->string('email')->nullable()->after('name');
            }
            if (!Schema::hasColumn('messages', 'subject')) {
                $table->string('subject')->nullable()->after('email');
            }
            if (Schema::hasColumn('messages', 'sender_id')) {
                $table->unsignedBigInteger('sender_id')->nullable()->change();
            }
            $table->dropForeign(['sender_id', 'recipient_id']);
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['name', 'email', 'subject']);
            if (Schema::hasColumn('messages', 'message')) {
                $table->renameColumn('message', 'content');
            }
            $table->unsignedBigInteger('sender_id')->nullable(false)->change();
            $table->dropForeign(['sender_id', 'recipient_id']);
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
}
