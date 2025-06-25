<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Reservation;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index()
    {
        // Core statistics
        $totalBooks = Book::count(); // Total unique book titles
        $totalCopies = Book::sum('total_copies') ?? $totalBooks; // Sum of all copies
        $availableCopies = Book::sum('available_copies') ?? $totalBooks; // Sum of available copies
        $borrowedBooks = Borrow::whereNull('return_date')
            ->where('status', 'borrowed')
            ->count(); // Currently borrowed books
        $borrowedCopies = $borrowedBooks; // Alias for consistency
        $overdueBooks = Borrow::whereNull('return_date')
            ->where('status', 'borrowed')
            ->where('due_date', '<', Carbon::now())
            ->count(); // Overdue books
        $reservedCopies = Reservation::where('status', 'pending')
            ->count(); // Pending reservations
        $availableBooks = $totalBooks - $borrowedBooks; // Explicitly define available books

        // Books list for table
        $books = Book::select('id', 'title', 'author', 'isbn', 'total_copies', 'available_copies')
            ->withCount(['borrows as borrowed_count' => function ($query) {
                $query->whereNull('return_date')->where('status', 'borrowed');
            }])
            ->orderBy('title')
            ->get();

        return view('admin.statistics.index', compact(
            'totalBooks',
            'totalCopies',
            'availableCopies',
            'borrowedBooks',
            'borrowedCopies',
            'overdueBooks',
            'reservedCopies',
            'availableBooks',
            'books'
        ));
    }
}