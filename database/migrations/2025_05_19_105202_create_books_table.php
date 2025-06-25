<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            
            // Book Details
            $table->string('title')->index()->comment('Book title for search and display');
            $table->text('description')->nullable()->comment('Book synopsis or description');
            $table->string('isbn', 13)->unique()->nullable()->index()->comment('International Standard Book Number, unique identifier');
            $table->date('published_at')->nullable()->comment('Original publication date');

            // Relationships
            $table->foreignId('author_id')
                ->nullable()
                ->constrained('authors')
                ->onDelete('set null')
                ->comment('Links to the author of the book');
            $table->foreignId('publisher_id')
                ->nullable()
                ->constrained('publishers')
                ->onDelete('set null')
                ->comment('Links to the publisher of the book');
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->onDelete('set null')
                ->after('publisher_id')
                ->comment('Links to the book category');

            // File Management
            $table->string('file_path')->nullable()->comment('Path to digital book file, if available');
            $table->string('cover_image_path')->nullable()->comment('Path to book cover image');

            // Status Management
            $table->enum('status', ['available', 'borrowed', 'reserved', 'maintenance'])
                ->default('available')
                ->index()
                ->comment('Current status of the book');
            $table->boolean('is_available')
                ->default(true)
                ->comment('Indicates if the book is available for borrowing');
            $table->boolean('is_purchasable')
                ->default(false)
                ->comment('Indicates if the book can be purchased');

            // Financial
            $table->decimal('price', 8, 2)
                ->nullable()
                ->comment('Purchase price of the book, if purchasable');
            $table->decimal('rental_price', 8, 2)
                ->nullable()
                ->comment('Rental price per borrowing period, if applicable');

            // Timestamps
            $table->timestamps();
            $table->softDeletes()->comment('Soft delete for recovering deleted books');
        });
    }

    public function down()
    {
        Schema::dropIfExists('books');
    }
};