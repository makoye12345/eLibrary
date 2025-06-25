<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function index()
    {
        $books = Book::all();
        $users = User::where('is_admin', false)->get(); // Exclude admins from borrowers
        $borrowings = Transaction::with('book', 'borrower')->orderBy('borrowed_at', 'desc')->get();
        return view('admin.borrowings.index', compact('books', 'users', 'borrowings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'borrower_id' => 'required|exists:users,id',
            'borrowed_at' => 'required|date',
            'returned_at' => 'nullable|date|after_or_equal:borrowed_at',
        ]);

        Transaction::create([
            'book_id' => $request->book_id,
            'user_id' => $request->borrower_id,
            'borrowed_at' => $request->borrowed_at,
            'returned_at' => $request->returned_at,
            'due_date' => \Carbon\Carbon::parse($request->borrowed_at)->addDays(14), // Example: 14-day borrowing period
        ]);

        return redirect()->route('admin.borrowings.index')->with('success', 'Borrowing added successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:transactions,id',
            'book_id' => 'required|exists:books,id',
            'borrower_id' => 'required|exists:users,id',
            'borrowed_at' => 'required|date',
            'returned_at' => 'nullable|date|after_or_equal:borrowed_at',
            'status' => 'required|in:Borrowed,Returned',
        ]);

        $borrowing = Transaction::findOrFail($request->id);
        $borrowing->update([
            'book_id' => $request->book_id,
            'user_id' => $request->borrower_id,
            'borrowed_at' => $request->borrowed_at,
            'returned_at' => $request->status === 'Returned' ? ($request->returned_at ?? now()) : null,
            'due_date' => \Carbon\Carbon::parse($request->borrowed_at)->addDays(14),
        ]);

        return redirect()->route('admin.borrowings.index')->with('success', 'Borrowing updated successfully!');
    }

    public function destroy($id)
    {
        $borrowing = Transaction::findOrFail($id);
        $borrowing->delete();
        return redirect()->route('admin.borrowings.index')->with('success', 'Borrowing deleted successfully!');
    }
    public function edit($id)
{
    $borrowing = Borrowing::findOrFail($id);
    return view('admin.borrowings.edit', compact('borrowing'));
}

}