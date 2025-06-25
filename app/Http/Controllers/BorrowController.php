<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BorrowController extends Controller
{
    /**
     * Display the user's currently borrowed books.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $borrowedBooks = Borrow::where('user_id', Auth::id())
            ->where('status', 'borrowed')
            ->with('book')
            ->latest()
            ->get()
            ->map(function ($borrow) {
                $isOverdue = Carbon::now()->greaterThan($borrow->due_date);
                return [
                    'id' => $borrow->id,
                    'book_title' => $borrow->book->title,
                    'borrowed_at' => $borrow->borrowed_at->format('Y-m-d'),
                    'due_date' => $borrow->due_date->format('Y-m-d'),
                    'status' => $isOverdue ? 'Overdue' : 'On time',
                    'can_renew' => !$isOverdue && $borrow->renewal_count < 2,
                ];
            });

        return view('user.borrows.index', compact('borrowedBooks'));
    }

    /**
     * Renew a borrowed book.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $borrowId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function renewBook(Request $request, $borrowId)
    {
        try {
            $borrow = Borrow::where('id', $borrowId)
                ->where('user_id', Auth::id())
                ->where('status', 'borrowed')
                ->firstOrFail();

            if (Carbon::now()->greaterThan($borrow->due_date)) {
                return redirect()->back()->with('error', 'Cannot renew overdue book.');
            }

            if ($borrow->renewal_count >= 2) {
                return redirect()->back()->with('error', 'Maximum renewal limit reached.');
            }

            $borrow->due_date = Carbon::parse($borrow->due_date)->addDays(14);
            $borrow->renewal_count += 1;
            $borrow->save();

            return redirect()->back()->with('success', 'Book renewed successfully.');
        } catch (\Exception $e) {
            \Log::error('Error renewing book: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to renew book.');
        }
    }

    /**
     * Search for books by title, author, or ISBN.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function searchBooks(Request $request)
    {
        $searchTerm = $request->input('search', '');

        $books = Book::query()
            ->when($searchTerm, function ($query, $searchTerm) {
                $query->where('title', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('author', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('isbn', 'LIKE', "%{$searchTerm}%");
            })
            ->get()
            ->map(function ($book) {
                // Check if the book is currently borrowed by the user
                $isBorrowed = Borrow::where('user_id', Auth::id())
                    ->where('book_id', $book->id)
                    ->where('status', 'borrowed')
                    ->exists();
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'isbn' => $book->isbn,
                    'description' => $book->description,
                    'is_borrowed' => $isBorrowed,
                ];
            });

        return view('user.books.search', compact('books', 'searchTerm'));
    }
    
    
}