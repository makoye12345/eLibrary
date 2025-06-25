<?php

namespace App\Imports;

use App\Models\Book;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BooksImport implements ToModel, WithHeadingRow
{
    /**
     * Map each row in Excel to a Book model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Find or create the category
        $category = Category::firstOrCreate(
            ['name' => $row['category']],
            ['name' => $row['category']]
        );

        // Optional: Handle file paths
        $filePath = $row['file_path'] ?? null;
        $coverImagePath = $row['cover'] ?? null;

        // Check if ISBN already exists
        if (Book::where('isbn', $row['isbn'])->exists()) {
            return null; // skip the row instead of throwing error
        }

        return new Book([
            'title' => $row['title'],
            'author' => $row['author'],
            'isbn' => $row['isbn'],
            'category_id' => $category->id,
            'file_path' => $filePath,
            'cover_image_path' => $coverImagePath,
        ]);
    }
}
