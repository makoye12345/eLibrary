<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // First row data
        $totalBooks = Book::count();
        $activeUsers = User::where('status', 'active')->count();
        $booksBorrowed = Transaction::whereNull('returned_at')->count();
        $books = Book::select('id', 'title', 'category', 'is_borrowed')->get();

        // Second row data
        $issuedBooks = Transaction::whereNull('returned_at')->count();
        $returnedBooks = Transaction::whereNotNull('returned_at')->count();
        $notReturnedBooks = Transaction::whereNull('returned_at')
            ->where('due_date', '<', Carbon::today())
            ->count();
        $todayDate = Carbon::today()->format('m/d/Y');

        // Debug: Log variables to verify they are defined
        \Log::info('Dashboard variables', [
            'totalBooks' => $totalBooks,
            'activeUsers' => $activeUsers,
            'booksBorrowed' => $booksBorrowed,
            'issuedBooks' => $issuedBooks,
            'returnedBooks' => $returnedBooks,
            'notReturnedBooks' => $notReturnedBooks,
            'todayDate' => $todayDate,
            'books_count' => $books->count(),
        ]);

        return view('admin.dashboard', compact(
            'totalBooks',
            'activeUsers',
            'booksBorrowed',
            'books',
            'issuedBooks',
            'returnedBooks',
            'notReturnedBooks',
            'todayDate'
        ));
    }

  
}