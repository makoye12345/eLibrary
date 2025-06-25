<?php

namespace Database\Seeders;

use App\Models\AccessLog;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookLoan;
use App\Models\Category;
use App\Models\Fine;
use App\Models\Message;
use App\Models\Publisher;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Borrow;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Exception;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::beginTransaction();

            // Create users
            $user1 = User::create(['name' => 'John Doe', 'email' => 'john@example.com', 'password' => bcrypt('password')]);
            $user2 = User::create(['name' => 'Jane Smith', 'email' => 'jane@example.com', 'password' => bcrypt('password')]);

            // Create books
            $book1 = Book::create(['title' => 'Python Programming']);
            $book2 = Book::create(['title' => 'Data Science Basics', 'added_date' => Carbon::now()->subDays(2)]);

            // Create borrows
            Borrow::create(['user_id' => $user1->id, 'book_id' => $book1->id, 'due_date' => Carbon::now()->addDay()]);
            Borrow::create(['user_id' => $user2->id, 'book_id' => $book2->id, 'due_date' => Carbon::now()->subDay()]);

            // Create reservation
            Reservation::create(['user_id' => $user1->id, 'book_id' => $book2->id]);


            Book::create(['title' => 'Book 1', 'status' => 'available']);
        Book::create(['title' => 'Book 2', 'status' => 'borrowed']);
        Book::create(['title' => 'Book 3', 'status' => 'overdue']);
        Book::create(['title' => 'Book 4', 'status' => 'reserved']);
        // Add more books as needed (e.g., 500 total)

        // Add borrows
        Borrow::create(['book_id' => 2, 'borrowed_at' => Carbon::today(), 'returned_at' => null]);
        Borrow::create(['book_id' => 3, 'borrowed_at' => Carbon::today()->subDays(10), 'returned_at' => null]);
        // Add more borrows for the last 30 days

            // Create admin user
            $admin = User::create([
                'username' => 'admin',
                'name' => 'Test Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'first_name' => 'Test',
                'last_name' => 'Admin',
                'role' => 'admin',
                'status' => 'active',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // Create a regular user
            $user = User::create([
                'username' => 'user',
                'name' => 'Another User',
                'email' => 'user@example.com',
                'password' => bcrypt('password'),
                'first_name' => 'Another',
                'last_name' => 'User',
                'role' => 'member',
                'status' => 'active',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // Create supporting data
            $author = Author::create(['name' => 'Jane Doe']);
            $publisher = Publisher::create(['name' => 'Acme Publishing']);
            $category = Category::create(['name' => 'Fiction']);

            // Create books
            $book3 = Book::create([
                'title' => 'Sample Book 1',
                'author_id' => $author->id,
                'publisher_id' => $publisher->id,
                'category_id' => $category->id,
                'isbn' => '1234567890123',
                'description' => 'A thrilling novel about adventure and discovery.',
                'published_at' => now(),
                'file_path' => null,
                'cover_image_path' => null,
                'status' => 'borrowed',
                'is_available' => false,
                'is_purchasable' => true,
                'price' => 19.99,
                'rental_price' => 2.99,
            ]);

            $book4 = Book::create([
                'title' => 'Sample Book 2',
                'author_id' => $author->id,
                'publisher_id' => $publisher->id,
                'category_id' => $category->id,
                'isbn' => '9876543210987',
                'description' => 'A deep dive into historical events.',
                'published_at' => now(),
                'file_path' => null,
                'cover_image_path' => null,
                'status' => 'available',
                'is_available' => true,
                'is_purchasable' => false,
                'rental_price' => 1.99,
            ]);

            // Create book loans
            $loan1 = BookLoan::create([
                'user_id' => $admin->id,
                'book_id' => $book3->id,
                'borrowed_at' => Carbon::now()->subDays(5),
                'due_date' => Carbon::now()->subDays(1),
                'returned_at' => null,
            ]);

            $loan2 = BookLoan::create([
                'user_id' => $admin->id,
                'book_id' => $book3->id,
                'borrowed_at' => Carbon::now()->subDays(10),
                'due_date' => Carbon::now()->subDays(3),
                'returned_at' => Carbon::now()->subDays(2),
            ]);

            // Create a purchase
            Purchase::create([
                'user_id' => $user->id,
                'book_id' => $book3->id,
                'amount' => $book3->price,
                'purchased_at' => Carbon::now(),
                'status' => 'completed',
            ]);

            // Create fines
            Fine::create([
                'user_id' => $user->id,
                'book_loan_id' => $loan1->id,
                'amount' => 15.00,
                'status' => 'pending',
                'issued_at' => Carbon::now(),
            ]);

            // Create access logs
            AccessLog::create([
                'timestamp' => Carbon::now()->subHours(2),
                'username' => 'Test Admin',
                'platform' => 'Windows',
                'ip_address' => '127.0.0.1',
                'browser' => 'Chrome',
                'type' => 'access',
                'user_id' => $admin->id,
            ]);

            AccessLog::create([
                'timestamp' => Carbon::now()->subHours(1),
                'username' => 'Test Admin',
                'platform' => 'Mac OS',
                'ip_address' => '192.168.1.1',
                'browser' => 'Safari',
                'type' => 'login',
                'user_id' => $admin->id,
            ]);

            // Create messages
            Message::create([
                'sender_id' => $admin->id,
                'receiver_id' => $user->id,
                'message' => 'Hello, please return the book.',
                'type' => 'individual',
                'sent_at' => Carbon::now()->subHours(3),
                'is_read' => false,
                'subject' => 'Book Reminder',
            ]);

            Message::create([
                'sender_id' => $user->id,
                'receiver_id' => $admin->id,
                'message' => 'I will return the book tomorrow.',
                'type' => 'individual',
                'sent_at' => Carbon::now()->subHours(1),
                'is_read' => false,
                'subject' => 'Book Return Update',
            ]);

            Message::create([
                'sender_id' => $admin->id,
                'receiver_id' => null,
                'message' => 'Library will be closed tomorrow.',
                'type' => 'group',
                'sent_at' => Carbon::now()->subHours(2),
                'is_read' => false,
                'subject' => 'Library Closure',
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            \Log::error('Database seeding error: ' . $e->getMessage());
            throw new Exception('Failed to seed database: ' . $e->getMessage());
        }
    }
}
