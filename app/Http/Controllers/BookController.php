<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Reservation;
use App\Models\Fine;
use App\Notifications\BookAvailableNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web')->except(['adminIndex', 'adminShow', 'adminReturnedBooks']);
        $this->middleware(['auth:admin', 'is_admin'])->only(['adminIndex', 'adminShow', 'adminReturnedBooks']);
    }

    /**
     * Display the user dashboard with books and fines.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch available books (global list)
        $books = Book::with(['category', 'publisher'])
            ->available()
            ->orderBy('title')
            ->paginate(12);

        // Fetch the user's borrowed books
        $borrowedBooks = Borrow::with(['book.category', 'book.publisher'])
            ->where('user_id', Auth::guard('web')->id())
            ->whereNull('returned_at')
            ->where('status', 'borrowed')
            ->whereHas('book') // Ensure book exists
            ->get();

        // Fetch the user's fines and overdue borrows
        $user = Auth::guard('web')->user();
        $fines = $user->fines()->with(['borrow.book'])->where('status', 'pending')->get();
        $overdueBorrows = Borrow::with(['book'])
            ->overdue()
            ->where('user_id', $user->id)
            ->whereHas('book') // Ensure book exists
            ->get();

        // Log for debugging
        Log::info('Dashboard loaded', [
            'user_id' => $user->id,
            'available_books' => $books->pluck('id')->toArray(),
            'borrowed_books' => $borrowedBooks->pluck('book_id')->toArray(),
            'fines_count' => $fines->count(),
            'overdue_borrows' => $overdueBorrows->pluck('id')->toArray(),
        ]);

        // Prevent caching to ensure fresh data
        return response()
            ->view('user.books.index', compact('books', 'borrowedBooks', 'fines', 'overdueBorrows'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Search books and borrowed books.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $query = $request->input('query', '');

        // Fetch available books based on search query
        $books = Book::with(['category', 'publisher'])
            ->available()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('author', 'like', "%{$query}%")
                  ->orWhere('isbn', 'like', "%{$query}%")
                  ->orWhereHas('category', function($q) use ($query) {
                      $q->where('name', 'like', "%{$query}%");
                  });
            })
            ->orderBy('title')
            ->paginate(12)
            ->appends(['query' => $query]);

        // Fetch the user's borrowed books based on search query
        $borrowedBooks = Borrow::with(['book.category', 'book.publisher'])
            ->where('user_id', Auth::guard('web')->id())
            ->whereNull('returned_at')
            ->where('status', 'borrowed')
            ->whereHas('book') // Ensure book exists
            ->whereHas('book', function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('author', 'like', "%{$query}%")
                  ->orWhere('isbn', 'like', "%{$query}%")
                  ->orWhereHas('category', function($q) use ($query) {
                      $q->where('name', 'like', "%{$query}%");
                  });
            })
            ->get();

        // Fetch the user's fines and overdue borrows
        $user = Auth::guard('web')->user();
        $fines = $user->fines()->with(['borrow.book'])->where('status', 'pending')->get();
        $overdueBorrows = Borrow::with(['book'])
            ->overdue()
            ->where('user_id', $user->id)
            ->whereHas('book') // Ensure book exists
            ->get();

        // Prevent caching
        return response()
            ->view('user.books.index', compact('books', 'borrowedBooks', 'query', 'fines', 'overdueBorrows'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Display a specific book.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::with(['category', 'publisher', 'borrows', 'reservations'])->findOrFail($id);
        
        $user = Auth::guard('web')->user() ?? Auth::guard('admin')->user();
        $hasBorrowed = $user && (
            ($user->role === 'admin' && Auth::guard('admin')->check()) ||
            $user->hasBorrowed($book->id)
        );
        
        $hasReserved = $user && $book->reservations()
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        $similarBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->available()
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('user.books.show', compact('book', 'hasBorrowed', 'hasReserved', 'similarBooks'));
    }

    /**
     * Show the borrow form for a book.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function showBorrowForm($id)
    {
        $book = Book::available()->findOrFail($id);
        
        if (Auth::guard('web')->user()->hasBorrowed($book->id)) {
            return redirect()->route('books.show', $id)
                ->with('error', 'You have already borrowed this book.');
        }

        return view('user.books.borrow-form', compact('book'));
    }

    /**
     * Store a new borrow record.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function borrowStore(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'remarks' => 'nullable|string|max:500',
            'due_date' => 'nullable|date|after:today',
        ]);

        if ($validator->fails()) {
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => $validator->errors()->first()], 422)
                : redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $book = Book::available()->findOrFail($id);
            $user = Auth::guard('web')->user();

            if ($user->hasBorrowed($book->id)) {
                throw new \Exception('You have already borrowed this book.');
            }

            $dueDate = $request->input('due_date') 
                ? Carbon::parse($request->input('due_date'))
                : now()->addDays(14);

            $borrow = Borrow::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'borrowed_at' => now(),
                'due_at' => $dueDate,
                'remarks' => $request->input('remarks'),
                'status' => 'borrowed',
            ]);

            $book->update([
                'available_copies' => $book->available_copies - 1,
                'status' => $book->available_copies > 1 ? 'available' : 'borrowed',
            ]);

            DB::commit();

            Log::info('Book borrowed', [
                'book_id' => $book->id,
                'user_id' => $user->id,
                'available_copies' => $book->available_copies,
                'status' => $book->status,
            ]);

            return $request->ajax()
                ? response()->json([
                    'success' => true,
                    'message' => 'Book borrowed successfully! Due: ' . $borrow->due_at->format('M d, Y'),
                    'redirect' => route('books.index')
                ])
                : redirect()->route('books.index')
                    ->with('success', 'Book borrowed successfully! Due: ' . $borrow->due_at->format('M d, Y'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Borrow error: ' . $e->getMessage(), [
                'book_id' => $id,
                'user_id' => Auth::guard('web')->id(),
            ]);

            return $request->ajax()
                ? response()->json(['success' => false, 'message' => 'Failed to borrow: ' . $e->getMessage()], 500)
                : redirect()->route('user.books.borrowed', $id)
                    ->with('error', 'Failed to borrow: ' . $e->getMessage());
        }
    }

    /**
     * Allow the user to read a borrowed book.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
     public function read($id)
    {
        try {
            $book = Book::findOrFail($id);
            $user = Auth::guard('web')->user() ?? Auth::guard('admin')->user();

            if ($user->role !== 'admin') {
                $borrow = Borrow::where('user_id', $user->id)
                    ->where('book_id', $id)
                    ->whereNull('returned_at')
                    ->where('status', 'borrowed')
                    ->firstOrFail();
            }

            return $this->serveBookFile($book);
        } catch (\Exception $e) {
            Log::error('Read error: ' . $e->getMessage(), [
                'book_id' => $id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('books.show', $id)->with('error', 
                $e->getMessage() === 'Book file not found.'
                    ? 'The book file is currently unavailable.'
                    : 'You need to borrow this book before reading.');
        }
    }

    /**
     * Serve the book file for reading.
     *
     * @param \App\Models\Book $book
     * @return \Illuminate\Http\Response
     */
    protected function serveBookFile(Book $book)
    {
        if (empty($book->file_path)) {
            throw new \Exception('No file associated with this book.');
        }

        $relativePath = $book->file_path;
        $fullPath = storage_path('app/public/' . $relativePath);

        if (!file_exists($fullPath) || !Storage::disk('public')->exists($relativePath)) {
            throw new \Exception('Book file not found.');
        }

        $extension = pathinfo($relativePath, PATHINFO_EXTENSION);

        return response()->file($fullPath, [
            'Content-Type' => $this->getContentType($extension),
            'Content-Disposition' => 'inline; filename="' . $book->slug . '.' . $extension . '"',
        ]);
    }

    /**
     * Get the content type for a file extension.
     *
     * @param string $extension
     * @return string
     */
    protected function getContentType($extension)
    {
        $types = [
            'pdf' => 'application/pdf',
            'epub' => 'application/epub+zip',
            'mobi' => 'application/x-mobipocket-ebook',
            'txt' => 'text/plain',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        return $types[strtolower($extension)] ?? 'application/octet-stream';
    }

    /**
     * Display the user's borrowed books.
     *
     * @return \Illuminate\Http\Response
     */
    public function borrowedBooks()
    {
        $borrows = Borrow::with(['book.category', 'book.publisher'])
            ->where('user_id', Auth::guard('web')->id())
            ->whereNull('returned_at')
            ->where('status', 'borrowed')
            ->whereHas('book') // Ensure book exists
            ->orderBy('due_at')
            ->paginate(10);

        return view('user.books.borrowed', compact('borrows'));
    }

    /**
     * Display the user's returned books.
     *
     * @return \Illuminate\Http\Response
     */
    public function returnedBooks()
    {
        $borrows = Borrow::with(['book.category', 'book.publisher'])
            ->where('user_id', Auth::guard('web')->id())
            ->whereNotNull('returned_at')
            ->where('status', 'returned')
            ->orderBy('returned_at', 'desc')
            ->paginate(10);

        return view('user.books.read', compact('borrows'));
    }

    /**
     * Return a borrowed book and calculate fines if overdue.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function returnBook($id)
    {
        DB::beginTransaction();

        try {
            $borrow = Borrow::where('id', $id)
                ->where('user_id', Auth::guard('web')->id())
                ->whereNull('returned_at')
                ->where('status', 'borrowed')
                ->firstOrFail();

            $borrow->update([
                'returned_at' => now(),
                'status' => 'returned',
            ]);

            $book = $borrow->book;
            $newAvailableCopies = $book->available_copies + 1;
            $book->update([
                'available_copies' => $newAvailableCopies,
                'status' => 'available',
            ]);

            // Calculate and store fine if overdue
            $fineAmount = $borrow->calculateFine();
            $fineMessage = '';
            if ($fineAmount > 0) {
                Fine::create([
                    'user_id' => $borrow->user_id,
                    'borrow_id' => $borrow->id,
                    'amount' => $fineAmount,
                    'status' => 'pending',
                    'description' => "Overdue fine for '{$book->title}' (Overdue by {$borrow->overdueDays()} days)",
                ]);
                $fineMessage = " A fine of TSh " . number_format($fineAmount, 2) . " has been issued.";
            }

            $this->handleBookReturnNotifications($book);

            DB::commit();

            Log::info('Book returned', [
                'book_id' => $book->id,
                'user_id' => Auth::guard('web')->id(),
                'available_copies' => $newAvailableCopies,
                'status' => $book->status,
                'fine_amount' => $fineAmount,
            ]);

            return request()->ajax()
                ? response()->json([
                    'success' => true,
                    'message' => 'Book returned successfully!' . $fineMessage,
                    'redirect' => route('books.index')
                ])
                : redirect()->route('books.index')->with('success', 'Book returned successfully!' . $fineMessage);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Return error: ' . $e->getMessage(), [
                'borrow_id' => $id,
                'user_id' => Auth::guard('web')->id(),
            ]);

            return request()->ajax()
                ? response()->json(['success' => false, 'message' => 'Failed to return book: ' . $e->getMessage()], 500)
                : redirect()->route('books.borrowed')->with('error', 'Failed to return book: ' . $e->getMessage());
        }
    }

    /**
     * Handle notifications for book return.
     *
     * @param \App\Models\Book $book
     * @return void
     */
    protected function handleBookReturnNotifications(Book $book)
    {
        $reservations = $book->reservations()
            ->where('status', 'pending')
            ->with('user')
            ->get();

        foreach ($reservations as $reservation) {
            $reservation->update(['status' => 'fulfilled']);
            $reservation->user->notify(new BookAvailableNotification($book));
        }
    }

    /**
     * Reserve a book.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function reserveBook(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $book = Book::findOrFail($id);
            $user = Auth::guard('web')->user();

            if ($book->isAvailable()) {
                throw new \Exception('This book is currently available for borrowing.');
            }

            if ($user->hasReserved($book->id)) {
                throw new \Exception('You have already reserved this book.');
            }

            if ($user->hasBorrowed($book->id)) {
                throw new \Exception('You have already borrowed this book.');
            }

            Reservation::create([
                'user_id' => $user->id,
                'book_id' => $id,
                'status' => 'pending',
                'reserved_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('books.show', $id)
                ->with('success', 'Book reserved successfully! You will be notified when available.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reservation error: ' . $e->getMessage());

            return redirect()->route('books.show', $id)
                ->with('error', 'Failed to reserve: ' . $e->getMessage());
        }
    }

    /**
     * Display the user's reserved books.
     *
     * @return \Illuminate\Http\Response
     */
    public function reservedBooks()
    {
        $reservations = Reservation::with(['book.category', 'book.publisher'])
            ->where('user_id', Auth::guard('web')->id())
            ->pending()
            ->orderBy('reserved_at')
            ->paginate(10);

        return view('user.books.reserved', compact('reservations'));
    }

    /**
     * Cancel a reservation.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function cancelReservation($id)
    {
        DB::beginTransaction();

        try {
            $reservation = Reservation::where('id', $id)
                ->where('user_id', Auth::guard('web')->id())
                ->pending()
                ->firstOrFail();

            $reservation->update([
                'status' => 'canceled',
                'canceled_at' => now(),
            ]);

            DB::commit();

            return request()->ajax()
                ? response()->json(['success' => true, 'message' => 'Reservation canceled'])
                : redirect()->route('books.reserved')->with('success', 'Reservation canceled');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cancel reservation error: ' . $e->getMessage());

            return request()->ajax()
                ? response()->json(['success' => false, 'message' => 'Failed to cancel'], 500)
                : redirect()->back()->with('error', 'Failed to cancel reservation');
        }
    }

    /**
     * Display the user's fines and overdue borrows.
     *
     * @return \Illuminate\Http\Response
     */
    public function fines()
    {
        // Fetch the user's fines, overdue borrows, and borrowed books
        $user = Auth::guard('web')->user();
        $fines = $user->fines()->with(['borrow.book'])->where('status', 'pending')->get();
        $overdueBorrows = Borrow::with(['book'])
            ->overdue()
            ->where('user_id', $user->id)
            ->whereHas('book') // Ensure book exists
            ->get();
        $borrowedBooks = Borrow::with(['book.category', 'book.publisher'])
            ->where('user_id', $user->id)
            ->whereNull('returned_at')
            ->where('status', 'borrowed')
            ->whereHas('book') // Ensure book exists
            ->get();

        // Log for debugging
        Log::info('Fines page loaded', [
            'user_id' => $user->id,
            'fines_count' => $fines->count(),
            'overdue_borrows' => $overdueBorrows->pluck('id')->toArray(),
            'borrowed_books' => $borrowedBooks->pluck('book_id')->toArray(),
        ]);

        // Prevent caching to ensure fresh data
        return response()
            ->view('user.fines.index', compact('fines', 'overdueBorrows', 'borrowedBooks'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Display the admin book list.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndex()
    {
        $books = Book::with(['category', 'publisher'])
            ->withCount(['borrows', 'reservations'])
            ->latest()
            ->paginate(15);

        return view('admin.books.index', compact('books'));
    }

    /**
     * Display a specific book for admin.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function adminShow($id)
    {
        $book = Book::with(['borrows.user', 'reservations.user'])
            ->withCount(['borrows', 'reservations'])
            ->findOrFail($id);

        return view('admin.books.show', compact('book'));
    }

    /**
     * Display returned books for admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function adminReturnedBooks(Request $request)
    {
        $query = $request->input('query', '');

        $borrows = Borrow::with(['book', 'user'])
            ->whereNotNull('returned_at')
            ->where('status', 'returned')
            ->when($query, function ($q) use ($query) {
                $q->whereHas('book', function ($qb) use ($query) {
                    $qb->where('title', 'like', "%{$query}%")
                       ->orWhere('isbn', 'like', "%{$query}%")
                       ->orWhere('author', 'like', "%{$query}%");
                })->orWhereHas('user', function ($qu) use ($query) {
                    $qu->where('name', 'like', "%{$query}%")
                       ->orWhere('email', 'like', "%{$query}%");
                });
            })
            ->orderBy('returned_at', 'desc')
            ->paginate(10)
            ->appends(['query' => $query]);

        Log::info('Admin Returned Books Query', [
            'query' => $query,
            'borrows_count' => $borrows->count(),
            'borrows_data' => $borrows->pluck('id')->toArray(),
        ]);

        return view('admin.books.returned', compact('borrows', 'query'));
    }

    public function borrow(Book $book)
{
    $user = auth()->user();
    $maxBooksPerDay = 3;

    // Check if user has reached borrowing limit
    $borrowedToday = $user->borrowedBooks()
        ->whereDate('created_at', today())
        ->where('returned', false)
        ->count();

    if ($borrowedToday >= $maxBooksPerDay) {
        return redirect()->back()->with('error', 
            'Umefikia kikomo cha vitabu ' . $maxBooksPerDay . ' kwa siku ya leo. Tafadhali subiri kesho!');
    }

    // Check if book is available
    if (!$book->is_available || $book->status !== 'available') {
        return redirect()->back()->with('error', 'Kitabu hiki hakipatikani kwa sasa.');
    }

    // Create borrow record
    Borrow::create([
        'user_id' => $user->id,
        'book_id' => $book->id,
        'borrowed_date' => now(),
        'return_date' => now()->addDays(14), // 2 weeks to return
        'returned' => false
    ]);

    // Update book availability
    $book->update(['is_available' => false]);

    return redirect()->back()->with('success', 'Kitabu kimekopwa kwa mafanikio!');
}
    
} 