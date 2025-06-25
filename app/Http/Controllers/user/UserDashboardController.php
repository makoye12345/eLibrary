<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookLoan;
use App\Models\Notification;
use App\Models\Purchase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Exception;

class UserDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(): View
    {
        $user = Auth::user();

        try {
            $borrowedBooks = $user->borrowedBooks()
                ->with(['book.author', 'book.publisher'])
                ->whereNull('returned_at')
                ->orderBy('due_date', 'asc')
                ->paginate(5);

            $overdueCount = $user->borrowedBooks()
                ->whereNull('returned_at')
                ->where('due_date', '<', now())
                ->count();

            $returnedBooksCount = $user->borrowedBooks()
                ->whereNotNull('returned_at')
                ->count();

            $pendingFines = $user->fines()
                ->where('status', 'pending')
                ->sum('amount');

            $notifications = $user->recipients()
                ->with('notification_details')
                ->latest()
                ->take(5)
                ->get();

            $recentBorrowings = $user->borrowedBooks()
                ->with(['book.author', 'book.publisher'])
                ->latest()
                ->take(5)
                ->get();

            return view('user.dashboard', [
                'user' => $user,
                'borrowedBooks' => $borrowedBooks,
                'overdueCount' => $overdueCount,
                'returnedBooksCount' => $returnedBooksCount,
                'pendingFines' => $pendingFines,
                'notifications' => $notifications,
                'recentBorrowings' => $recentBorrowings,
                'activeTab' => 'dashboard',
            ]);
        } catch (Exception $e) {
            \Log::error('Dashboard error: ' . $e->getMessage());
            return view('user.dashboard', [
                'user' => $user,
                'borrowedBooks' => collect()->paginate(5),
                'overdueCount' => 0,
                'returnedBooksCount' => 0,
                'pendingFines' => 0,
                'notifications' => collect(),
                'recentBorrowings' => collect(),
                'activeTab' => 'dashboard',
            ])->with('error', 'Failed to load dashboard data.');
        }
    }

    public function profile(): View
    {
        return view('user.profile', [
            'user' => Auth::user(),
            'activeTab' => 'profile',
        ]);
    }

    public function markNotificationAsRead(Notification $notification): \Illuminate\Http\RedirectResponse
    {
        try {
            if ($notification->recipients()->where('user_id', Auth::id())->exists()) {
                $notification->markAsReadForUser(Auth::user());
                return redirect()->back()->with('success', 'Notification marked as read.');
            }
            return redirect()->back()->with('error', 'Unauthorized action.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to mark notification as read.');
        }
    }

    public function notifications(): View
    {
        $notifications = Auth::user()->recipients()
            ->with('notification_details')
            ->latest()
            ->paginate(10);
        return view('user.notifications', [
            'notifications' => $notifications,
            'activeTab' => 'notifications',
        ]);
    }

    public function borrowings(): View
    {
        $borrowings = Auth::user()->borrowedBooks()
            ->with(['book', 'book.author', 'book.publisher'])
            ->latest()
            ->paginate(10);
        return view('user.borrowed.index', [
            'borrowedBooks' => $borrowings,
            'overdueCount' => $borrowings->where('due_date', '<', now())->count(),
            'activeTab' => 'borrowings',
        ]);
    }

    /**
     * Display the book search page and handle search queries.
     *
     * @param Request $request
     * @return View
     */
    public function searchBooks(Request $request): View
    {
        try {
            $query = $request->input('query');
            $category = $request->input('category');

            $books = Book::query()
                ->with(['author', 'publisher', 'category'])
                ->when($query, function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('isbn', 'like', "%{$query}%")
                      ->orWhereHas('author', function ($q) use ($query) {
                          $q->where('name', 'like', "%{$query}%");
                      });
                })
                ->when($category, function ($q) use ($category) {
                    $q->where('category_id', $category);
                })
                ->where('is_available', true)
                ->paginate(10)
                ->appends(['query' => $query, 'category' => $category]);

            $categories = \App\Models\Category::all();

            return view('user.books.search', [
                'books' => $books,
                'categories' => $categories,
                'query' => $query,
                'category' => $category,
                'activeTab' => 'search',
            ]);
        } catch (Exception $e) {
            \Log::error('Book search error: ' . $e->getMessage());
            return view('user.books.search', [
                'books' => collect()->paginate(10),
                'categories' => \App\Models\Category::all(),
                'query' => $query,
                'category' => $category,
                'activeTab' => 'search',
            ])->with('error', 'Failed to load search results.');
        }
    }

    /**
     * Borrow a book.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function borrowBook(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'book_id' => 'required|exists:books,id',
            ]);

            $user = Auth::user();
            $book = Book::findOrFail($request->book_id);

            if (!$book->is_available) {
                return response()->json(['message' => 'Book is not available.'], 400);
            }

            // Check if user already borrowed this book and hasn't returned it
            $existingLoan = $user->borrowedBooks()
                ->where('book_id', $book->id)
                ->whereNull('returned_at')
                ->exists();

            if ($existingLoan) {
                return response()->json(['message' => 'You have already borrowed this book.'], 400);
            }

            DB::transaction(function () use ($user, $book) {
                BookLoan::create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'borrowed_at' => now(),
                    'due_date' => now()->addDays(14), // 2-week borrowing period
                ]);

                $book->update(['is_available' => false]);

                Notification::create([
                    'title' => 'Book Borrowed',
                    'message' => "You have successfully borrowed '{$book->title}'. Please return by {$book->due_date->format('Y-m-d')}.",
                    'type' => 'borrow',
                    'created_by' => $user->id,
                    'is_urgent' => false,
                ])->recipients()->attach($user->id, ['delivery_status' => 'sent']);
            });

            return response()->json(['message' => 'Book borrowed successfully.'], 200);
        } catch (Exception $e) {
            \Log::error('Borrow book error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to borrow book.'], 500);
        }
    }

    /**
     * Buy a book.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function buyBook(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'book_id' => 'required|exists:books,id',
            ]);

            $user = Auth::user();
            $book = Book::findOrFail($request->book_id);

            if (!$book->is_purchasable) {
                return response()->json(['message' => 'Book is not available for purchase.'], 400);
            }

            DB::transaction(function () use ($user, $book) {
                Purchase::create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'amount' => $book->price ?? 29.99, // Default price if not set
                    'purchased_at' => now(),
                    'status' => 'completed',
                ]);

                Notification::create([
                    'title' => 'Book Purchased',
                    'message' => "You have successfully purchased '{$book->title}' for $" . number_format($book->price ?? 29.99, 2) . ".",
                    'type' => 'purchase',
                    'created_by' => $user->id,
                    'is_urgent' => false,
                ])->recipients()->attach($user->id, ['delivery_status' => 'sent']);
            });

            return response()->json(['message' => 'Book purchased successfully.'], 200);
        } catch (Exception $e) {
            \Log::error('Buy book error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to purchase book.'], 500);
        }
    }

    public function reservations(): View
    {
        return view('user.reservations', ['activeTab' => 'reservations']);
    }

    public function contactLibrarian(): View
    {
        return view('user.contact', ['activeTab' => 'contact']);
    }

    public function indexBooks(Request $request): View
{
    try {
        $books = Book::query()
            ->with(['author', 'publisher', 'category'])
            ->where('is_available', true)
            ->where('status', 'available')
            ->orderBy('title', 'asc')
            ->paginate(9);

        return view('user.books.index', [
            'books' => $books,
            'activeTab' => 'books',
        ]);
    } catch (Exception $e) {
        \Log::error('Books index error: ' . $e->getMessage());
        return view('user.books.index', [
            'books' => new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]),
                0,
                9,
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            ),
            'activeTab' => 'books',
        ])->with('error', 'Failed to load books.');
    }
}


} 