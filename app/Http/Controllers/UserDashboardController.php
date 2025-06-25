<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Log user info
        Log::debug('DashboardController: User Info', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        // Debug queries with raw SQL
        $borrowedBooksQuery = Borrow::where('user_id', $user->id)
            ->where('status', 'borrowed');
        $returnedBooksQuery = Borrow::where('user_id', $user->id)
            ->where('status', 'returned');
        $overdueBooksQuery = Borrow::where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->where('due_at', '<', now());

        // Log raw SQL
        Log::debug('DashboardController: Borrowed Books SQL', [
            'sql' => $borrowedBooksQuery->toSql(),
            'bindings' => $borrowedBooksQuery->getBindings(),
        ]);
        Log::debug('DashboardController: Returned Books SQL', [
            'sql' => $returnedBooksQuery->toSql(),
            'bindings' => $returnedBooksQuery->getBindings(),
        ]);
        Log::debug('DashboardController: Overdue Books SQL', [
            'sql' => $overdueBooksQuery->toSql(),
            'bindings' => $overdueBooksQuery->getBindings(),
        ]);

        // Execute queries
        $borrowedBooks = $borrowedBooksQuery->get();
        $returnedBooks = $returnedBooksQuery->get();
        $overdueBooks = $overdueBooksQuery->get();

        // Log results
        Log::debug('DashboardController: Borrowed Books', [
            'count' => $borrowedBooks->count(),
            'records' => $borrowedBooks->toArray(),
        ]);
        Log::debug('DashboardController: Returned Books', [
            'count' => $returnedBooks->count(),
            'records' => $returnedBooks->toArray(),
        ]);
        Log::debug('DashboardController: Overdue Books', [
            'count' => $overdueBooks->count(),
            'records' => $overdueBooks->toArray(),
        ]);

        // Calculate stats
        $stats = [
            'borrowed_books' => $borrowedBooks->count(),
            'returned_books' => $returnedBooks->count(),
            'pending_fines' => $overdueBooks->count() * 5.00, // $5 per overdue book
            'overdue_books' => $overdueBooks->count(),
            'reserved_books' => Reservation::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'total_books_read' => $returnedBooks->count(),
        ];

        // Log stats
        Log::debug('DashboardController: Stats', $stats);

        // Fetch recent activities
        $recentActivities = collect()
            ->merge(
                Borrow::where('user_id', $user->id)
                    ->with('book')
                    ->latest('created_at')
                    ->take(5)
                    ->get()
                    ->map(function ($borrow) {
                        $description = $borrow->status === 'borrowed'
                            ? "Borrowed '{$borrow->book->title}'"
                            : "Returned '{$borrow->book->title}'";
                        return (object) [
                            'description' => $description,
                            'created_at' => $borrow->created_at,
                        ];
                    })
            )
            ->merge(
                Reservation::where('user_id', $user->id)
                    ->with('book')
                    ->latest('created_at')
                    ->take(5)
                    ->get()
                    ->map(function ($reservation) {
                        $description = $reservation->status === 'pending'
                            ? "Reserved '{$reservation->book->title}'"
                            : "Cancelled reservation for '{$reservation->book->title}'";
                        return (object) [
                            'description' => $description,
                            'created_at' => $reservation->created_at,
                        ];
                    })
            )
            ->sortByDesc('created_at')
            ->take(5);

        return view('user.dashboard', compact('stats', 'recentActivities'));
    }
      public function notifications()
    {
        $user = Auth::user();

        // Fetch notifications
        $notifications = collect()
            ->merge(
                Borrow::where('user_id', $user->id)
                    ->where('status', 'borrowed')
                    ->where('due_at', '<', now())
                    ->with('book')
                    ->get()
                    ->map(function ($borrow) {
                        return (object) [
                            'type' => 'overdue',
                            'message' => "Book '{$borrow->book->title}' is overdue. Please return by {$borrow->due_at->format('M d, Y')}.",
                            'created_at' => $borrow->due_at,
                        ];
                    })
            )
            ->merge(
                Reservation::where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->with('book')
                    ->get()
                    ->map(function ($reservation) {
                        return (object) [
                            'type' => 'reservation',
                            'message' => "Reservation for '{$reservation->book->title}' is pending.",
                            'created_at' => $reservation->reserved_at,
                        ];
                    })
            )
            ->sortByDesc('created_at');

        return view('user.notifications', compact('notifications'));
    }

    public function clearNotifications(Request $request)
    {
        // For now, we don't have a notifications table, so we'll just redirect
        // If you add a notifications table, implement logic to mark as read or delete
        return redirect()->route('user.notifications')->with('success', 'Notifications cleared.');
    }
    
}