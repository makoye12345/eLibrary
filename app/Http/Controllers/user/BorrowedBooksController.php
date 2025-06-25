<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class BorrowedBooksController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the user's borrowed books.
     *
     * @return View
     */
    public function index(): View
    {
        try {
            $user = Auth::user();

            // Fetch borrowed books with relationships
            $borrowedBooks = $user->borrowedBooks()
                ->with(['book.author', 'book.publisher'])
                ->whereNull('returned_at')
                ->orderBy('due_date', 'asc')
                ->paginate(5);

            // Overdue books count
            $overdueCount = $user->borrowedBooks()
                ->whereNull('returned_at')
                ->where('due_date', '<', now())
                ->count();

            return view('user.books.borrowed', [
                'borrowedBooks' => $borrowedBooks,
                'overdueCount' => $overdueCount,
                'activeTab' => 'borrowings',
            ]);
        } catch (Exception $e) {
            \Log::error('Borrowed books index error: ' . $e->getMessage());

            // Create an empty paginated collection
            $emptyPaginatedBooks = new LengthAwarePaginator(
                collect([]), // Empty collection
                0, // Total items
                5, // Per page
                1, // Current page
                ['path' => request()->url(), 'query' => request()->query()]
            );

            return view('user.books.borrowed', [
                'borrowedBooks' => $emptyPaginatedBooks,
                'overdueCount' => 0,
                'activeTab' => 'borrowings',
            ])->with('error', 'Failed to load borrowed books.');
        }
    }

    /**
     * Request an extension for a borrowed book.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function requestExtension(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'book_loan_id' => 'required|exists:book_loans,id',
            ]);

            $user = Auth::user();
            $bookLoan = $user->borrowedBooks()->where('id', $request->book_loan_id)->firstOrFail();

            if ($bookLoan->returned_at) {
                return response()->json(['message' => 'This book has already been returned.'], 400);
            }

            // Example extension logic: extend due date by 7 days
            $newDueDate = $bookLoan->due_date->addDays(7);
            $bookLoan->update(['due_date' => $newDueDate]);

            // Notify user (assuming Notification model exists)
            \App\Models\Notification::create([
                'title' => 'Extension Requested',
                'message' => "Extension for '{$bookLoan->book->title}' approved until {$newDueDate->format('Y-m-d')}.",
                'type' => 'extension',
                'created_by' => $user->id,
                'is_urgent' => false,
            ])->recipients()->attach($user->id, ['delivery_status' => 'sent']);

            return response()->json(['message' => 'Extension requested successfully.'], 200);
        } catch (Exception $e) {
            \Log::error('Extension request error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to request extension.'], 500);
        }
    }
}