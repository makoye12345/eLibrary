<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\User;
use App\Models\Fine; // Assuming a Fine model for paid fines
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // First row data
        $totalBooks = Book::count();
        $activeUsers = User::where('status', 'active')->count();
        $booksBorrowed = Borrow::count(); // Total books ever borrowed (all borrow records)
        $books = Book::select('id', 'title', 'is_borrowed')->get();

        // Second row data
        $issuedBooks = Borrow::whereNull('returned_at')->count(); // Currently issued books
        $returnedBooks = Borrow::whereNotNull('returned_at')->count(); // Returned books
        $notReturnedBooks = Borrow::whereNull('returned_at')
            ->where('due_at', '<', Carbon::today())
            ->count(); // Overdue books
        $todayDate = Carbon::today()->format('m/d/Y');

        // Recent activities
        $recentActivities = collect();

        // New books added (assuming Book model has created_at)
        $newBooks = Book::select('id', 'title', 'created_at')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($book) {
                return (object) [
                    'type' => 'New Book',
                    'description' => "Added '{$book->title}' to library",
                    'created_at' => $book->created_at,
                ];
            });

        // New users registered (assuming User model has created_at)
        $newUsers = User::select('id', 'name', 'created_at')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($user) {
                return (object) [
                    'type' => 'New User',
                    'description' => "User '{$user->name}' registered",
                    'created_at' => $user->created_at,
                ];
            });

        // Books borrowed (assuming Borrow model has created_at)
        $borrowedBooks = Borrow::select('id', 'book_id', 'user_id', 'created_at')
            ->with(['book:id,title', 'user:id,name'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($borrow) {
                return (object) [
                    'type' => 'Book Borrowed',
                    'description' => "Book '{$borrow->book->title}' borrowed by {$borrow->user->name}",
                    'created_at' => $borrow->created_at,
                ];
            });

        // Books returned (assuming Borrow model has returned_at)
        $returnedBooksActivities = Borrow::select('id', 'book_id', 'user_id', 'returned_at')
            ->whereNotNull('returned_at')
            ->with(['book:id,title', 'user:id,name'])
            ->orderBy('returned_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($borrow) {
                return (object) [
                    'type' => 'Book Returned',
                    'description' => "Book '{$borrow->book->title}' returned by {$borrow->user->name}",
                    'created_at' => $borrow->returned_at,
                ];
            });

        // Fines paid (assuming Fine model with paid_at)
        $paidFines = Fine::select('id', 'user_id', 'amount', 'paid_at')
            ->whereNotNull('paid_at')
            ->with(['user:id,name'])
            ->orderBy('paid_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($fine) {
                return (object) [
                    'type' => 'Fine Paid',
                    'description' => "Fine of {$fine->amount} paid by {$fine->user->name}",
                    'created_at' => $fine->paid_at,
                ];
            });

        // Combine and sort activities by created_at, limit to 10 most recent
        $newUsers = User::select('id', 'name', 'created_at')
    ->orderBy('created_at', 'desc')
    ->take(5)
    ->get()
    ->map(function ($user) {
        return (object) [
            'id' => $user->id,
            'type' => 'New User',
            'description' => "User '{$user->name}' registered",
            'created_at' => $user->created_at,
        ];
    });


        // Debug: Log variables
        \Log::info('Dashboard variables', [
            'totalBooks' => $totalBooks,
            'activeUsers' => $activeUsers,
            'booksBorrowed' => $booksBorrowed,
            'issuedBooks' => $issuedBooks,
            'returnedBooks' => $returnedBooks,
            'notReturnedBooks' => $notReturnedBooks,
            'todayDate' => $todayDate,
            'books_count' => $books->count(),
            'recentActivities_count' => $recentActivities->count(),
        ]);

        return view('admin.dashboard', compact(
            'totalBooks',
            'activeUsers',
            'booksBorrowed',
            'books',
            'issuedBooks',
            'returnedBooks',
            'notReturnedBooks',
            'todayDate',
            'recentActivities'
        ));
    }
}