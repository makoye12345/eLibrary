<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use Illuminate\Http\Request;

class BorrowRequestController extends Controller
{
    public function index()
{
    // Fetch all borrow requests (optional: with user and book info)
    $borrowRequests = \App\Models\BookBorrowRequest::with('user', 'book')->get();

    // Return a view (hakikisha una view inayoitwa admin.borrow_requests.index)
    return view('admin.borrow_requests.index', compact('borrowRequests'));
}

    public function borrowBook(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $today = now()->format('Ymd');
        $lastBorrow = Borrow::whereDate('created_at', now()->toDateString())->count() + 1;
        $borrowCode = 'BRW-' . $today . '-' . str_pad($lastBorrow, 3, '0', STR_PAD_LEFT);

        $borrow = Borrow::create([
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'borrow_code' => $borrowCode,
            'borrowed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Book borrowed successfully!',
            'borrow_code' => $borrow->borrow_code,
        ]);
    }
}
