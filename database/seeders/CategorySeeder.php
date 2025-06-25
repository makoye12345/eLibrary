<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::create(['name' => 'Fiction', 'description' => 'Fictional books and novels']);
        Category::create(['name' => 'Non-Fiction', 'description' => 'Factual and informative books']);
        Category::create(['name' => 'Science Fiction', 'description' => 'Sci-fi and futuristic stories']);
    }
}