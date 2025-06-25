<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth; // Import correct Auth facade

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Book;
use App\Models\User;
use App\Models\Borrowing;

class AdminController extends Controller
{
    // Dashboard view
    public function index()
    {
        $totalBooks = Book::count();
        $activeUsers = User::where('active', true)->count();
        $booksBorrowed = Borrowing::where('status', 'borrowed')->count();
        $books = Book::all();

        return view('admin.dashboard', compact('totalBooks', 'activeUsers', 'booksBorrowed', 'books'));
    }

    // Manage books view
    public function manageBooks()
    {
        return view('admin.manage-books');
    }

    // Store a new book
    public function storeBook(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:255|unique:books,isbn',
            'book' => 'nullable|file|mimes:pdf|max:10000', // Max 10MB
        ]);

        $bookData = $request->only(['title', 'author', 'isbn']);
        if ($request->hasFile('book')) {
            $path = $request->file('book')->store('books', 'public');
            $bookData['book_path'] = $path;
        }

        $book = Book::create($bookData);

        return response()->json([
            'message' => 'Book added successfully',
            'book' => $book
        ], 201);
    }

    // Get all books
    public function getBooks()
    {
        $books = Book::all()->map(function ($book) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'isbn' => $book->isbn,
                'book' => $book->book_path ? Storage::url($book->book_path) : null,
                'is_borrowed' => $book->is_borrowed
            ];
        });

        return response()->json($books);
    }

    // Update a book
    public function updateBook(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:255|unique:books,isbn,' . $book->id,
            'book' => 'nullable|file|mimes:pdf|max:10000',
        ]);

        $bookData = $request->only(['title', 'author', 'isbn']);
        if ($request->hasFile('book')) {
            // Delete old file if exists
            if ($book->book_path) {
                Storage::disk('public')->delete($book->book_path);
            }
            $path = $request->file('book')->store('books', 'public');
            $bookData['book_path'] = $path;
        }

        $book->update($bookData);

        return response()->json([
            'message' => 'Book updated successfully',
            'book' => $book
        ]);
    }

    // Delete a book
    public function deleteBook($id)
    {
        $book = Book::findOrFail($id);

        if ($book->book_path) {
            Storage::disk('public')->delete($book->book_path);
        }

        $book->delete();

        return response()->json(['message' => 'Book deleted successfully']);
    }

    // Profile view
    public function profile()
    {
        return view('admin.profile');
    }

    // Handle profile image upload
    public function uploadProfileImage(Request $request)
    {
        if (!$request->hasFile('image')) {
            return redirect()->back()->with('error', 'No file was uploaded.');
        }

        if (!$request->file('image')->isValid()) {
            return redirect()->back()->with('error', 'Uploaded file is invalid.');
        }

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $path = $request->file('image')->store('profiles', 'public');
            if (!Storage::disk('public')->exists($path)) {
                return redirect()->back()->with('error', 'File was not stored in storage.');
            }

            $user = auth()->user();
            $user->profile_image = $path;
            $user->save();

            if ($user->profile_image !== $path) {
                return redirect()->back()->with('error', 'Failed to update profile image in database.');
            }

            return redirect()->route('admin.profile')->with('success', 'Profile image uploaded successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    // Update user profile (Name and Email)
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
        ]);

        $user = auth()->user();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function transactions()
    {
        return view('admin.transactions');
    }
    public function logout(Request $request)
    {
        Auth::logout(); // Ondoa Admin kutoka kwa session

        $request->session()->invalidate(); // Futia session ya admin
        $request->session()->regenerateToken(); // Regenerate CSRF token

        return redirect('/login')->with('message', 'You have been logged out successfully.');
    }
}