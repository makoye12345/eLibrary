<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = Borrow::with(['user', 'book'])
            ->whereHas('user')
            ->whereHas('book');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('book', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('author', 'like', "%{$search}%");
                });
            });
        }

        if ($status = $request->input('status')) {
            if ($status === 'overdue') {
                $query->where('status', 'borrowed')
                      ->where('due_at', '<', now());
            } else {
                $query->where('status', $status);
            }
        }

        $transactions = $query->orderBy('borrowed_at', 'desc')
                             ->paginate(15)
                             ->appends($request->query());

        return view('admin.transactions.index', compact('transactions'));
    }

    public function markReturned($id)
    {
        DB::beginTransaction();

        try {
            $transaction = Borrow::findOrFail($id);

            if ($transaction->status !== 'borrowed') {
                throw new \Exception('This book has already been returned or is not borrowed.');
            }

            $transaction->update([
                'returned_at' => now(),
                'status' => 'returned',
            ]);

            $book = $transaction->book;
            if ($book) {
                $book->update([
                    'is_available' => true,
                    'status' => 'available',
                ]);
            }

            DB::commit();

            return redirect()->route('admin.transactions.index')
                ->with('success', 'Book marked as returned successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Mark returned error: ' . $e->getMessage(), [
                'borrow_id' => $id,
                'user_id' => auth()->id(),
                'time' => now(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to mark as returned: ' . $e->getMessage());
        }
    }
}