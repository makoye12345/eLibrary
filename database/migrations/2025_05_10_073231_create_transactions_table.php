<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            
            // Transaction dates
            $table->date('borrowed_date');
            $table->date('due_date');
            $table->date('returned_date')->nullable();
            
            // Status tracking
            $table->enum('status', ['pending', 'borrowed', 'returned', 'overdue', 'lost'])
                  ->default('pending');
                  
            // Fines
            $table->decimal('fine_amount', 8, 2)->default(0);
            $table->boolean('fine_paid')->default(false);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'book_id']);
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};