<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show list of reservations with notifications
    public function index()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->where('status', '!=', 'canceled')
            ->with('book')
            ->orderBy('reserved_at', 'desc')
            ->paginate(6);

        $notificationCount = auth()->user()->unreadNotifications()->count();
        $notifications = auth()->user()->notifications()->latest()->take(5)->get();

        return view('reservations.index', compact('reservations', 'notificationCount', 'notifications'));
    }

    // Show form to create new reservation
    public function create()
    {
        $books = Book::where('is_available', true)->get();
        return view('reservations.create', compact('books'));
    }

    // Store new reservation
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        try {
            $book = Book::findOrFail($request->book_id);

            if (!$book->is_available) {
                return redirect()->route('reservations.create')->with('error', 'This book is not available for reservation.');
            }

            $existingReservation = Reservation::where('user_id', Auth::id())
                ->where('book_id', $book->id)
                ->where('status', '!=', 'canceled')
                ->exists();

            if ($existingReservation) {
                return redirect()->route('reservations.create')->with('error', 'You have already reserved this book.');
            }

            Reservation::create([
                'user_id' => Auth::id(),
                'book_id' => $request->book_id,
                'reserved_at' => now(),
                'status' => 'pending',
            ]);

            $book->update(['is_available' => false]);

            return redirect()->route('reservations.index')->with('success', 'Book reserved successfully!');
        } catch (\Exception $e) {
            Log::error('Reservation creation failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'book_id' => $request->book_id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('reservations.create')->with('error', 'Failed to reserve book: ' . $e->getMessage());
        }
    }

    // Cancel a reservation via Ajax / API
    public function cancel($id)
    {
        try {
            $reservation = Reservation::where('user_id', Auth::id())->findOrFail($id);

            if ($reservation->status === 'canceled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Reservation is already canceled',
                ], 400);
            }

            $reservation->update(['status' => 'canceled']);
            $reservation->book->update(['is_available' => true]);

            // Count remaining active reservations
            $remainingReservations = Reservation::where('user_id', Auth::id())
                ->where('status', '!=', 'canceled')
                ->count();

            return response()->json([
                'success' => true,
                'message' => 'Reservation canceled successfully',
                'book_id' => $reservation->book_id,
                'remaining_reservations' => $remainingReservations,
            ]);
        } catch (\Exception $e) {
            Log::error('Reservation cancellation failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'reservation_id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel reservation: ' . $e->getMessage(),
            ], 500);
        }
    }
}
