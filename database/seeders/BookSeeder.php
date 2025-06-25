<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run()
    {
        Book::create(['title' => 'The Great Gatsby', 'author' => 'F. Scott Fitzgerald']);
        Book::create(['title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee']);
        Book::create(['title' => '1984', 'author' => 'George Orwell']);
    }
}