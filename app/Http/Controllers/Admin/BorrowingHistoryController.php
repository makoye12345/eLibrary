<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BorrowingHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrowing::with(['user', 'book'])->orderBy('borrow_date', 'desc');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('book', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('author', 'like', "%{$search}%");
                })
                ->orWhere('status', 'like', "%{$search}%");
            });

            Log::info('Borrowing history search performed', [
                'search_term' => $search,
                'user_id' => auth()->id(),
            ]);
        }

        $borrowings = $query->paginate(10)->appends(['search' => $search]);

        Log::info('Admin accessed borrowing history', [
            'user_id' => auth()->id(),
            'borrowings_count' => $borrowings->total(),
        ]);

        return view('admin.borrowing-history.index', compact('borrowings', 'search'));
    }
}