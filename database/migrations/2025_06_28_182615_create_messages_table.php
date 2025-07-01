<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id')->nullable(); // Nullable for anonymous contact form submissions
            $table->unsignedBigInteger('recipient_id')->nullable(); // Nullable for broadcast or contact form messages
            $table->string('name')->nullable(); // Name for contact form submissions
            $table->string('email')->nullable(); // Email for contact form submissions
            $table->string('subject')->nullable(); // Subject for contact form submissions
            $table->text('message'); // Renamed from 'content' to match contact form
            $table->boolean('is_broadcast')->default(false); // True for messages to all users
            $table->timestamp('read_at')->nullable(); // When recipient read the message
            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
