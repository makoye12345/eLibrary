<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    public function run()
    {
        $user = User::first() ?? User::factory()->create();
        $book = Book::create(['title' => 'Sample Book']);
        Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrow_date' => now()->subDays(10),
            'due_date' => now()->subDays(3),
            'return_date' => null, // Overdue loan
        ]);
        Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrow_date' => now()->subDays(5),
            'due_date' => now()->addDays(5),
            'return_date' => null, // Borrowed
        ]);
        Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrow_date' => now()->subDays(15),
            'due_date' => now()->subDays(5),
            'return_date' => now()->subDays(2), // Returned
        ]);
    }
}