<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservedBooksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->with('book')
            ->orderBy('reserved_at', 'desc')
            ->paginate(6);
        return view('user.reservations', compact('reservations'));
    }

    public function create()
    {
        $books = Book::where('status', 'available')->get();
        return view('user.reserve-book', compact('books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        try {
            $book = Book::findOrFail($request->book_id);
            if ($book->status !== 'available') {
                return redirect()->back()->with('error', 'This book is not available for reservation.');
            }

            Reservation::create([
                'user_id' => Auth::id(),
                'book_id' => $request->book_id,
                'status' => 'pending',
            ]);

            $book->update(['status' => 'reserved']);

            return redirect()->route('reservations.index')->with('success', 'Book reserved successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to reserve book: ' . $e->getMessage());
        }
    }

    public function cancel($id)
    {
        try {
            $reservation = Reservation::where('user_id', Auth::id())->findOrFail($id);
            if ($reservation->status === 'canceled') {
                return response()->json(['success' => false, 'message' => 'Reservation already canceled']);
            }

            $reservation->update(['status' => 'canceled']);
            $reservation->book->update(['status' => 'available']);

            return response()->json(['success' => true, 'message' => 'Reservation canceled successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to cancel reservation: ' . $e->getMessage()], 500);
        }
    }
}
?>