<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BooksImport;
use App\Models\Borrow;
use Carbon\Carbon;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $books = Book::when($search, function ($query, $search) {
            return $query->where('title', 'like', "%{$search}%")
                        ->orWhere('author', 'like', "%{$search}%")
                        ->orWhereHas('category', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
        })->with('category')->paginate(12);

        $totalBooks = Book::count();

        return view('admin.books.index', compact('books', 'totalBooks'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'isbn' => 'required|string|max:13|unique:books,isbn',
                'category_id' => 'required|exists:categories,id',
                'description' => 'nullable|string',
                'book_file' => 'nullable|file|mimes:pdf,txt,epub|max:10000', // Updated to allow .pdf, .txt, .epub
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            ]);

            $book = new Book($validated);

            if ($request->hasFile('book_file')) {
                $book->file_path = $request->file('book_file')->store('books', 'public');
                Log::info('Book file uploaded: ' . $book->file_path);
            }

            if ($request->hasFile('cover_image')) {
                $book->cover_image_path = $request->file('cover_image')->store('book_covers', 'public');
                Log::info('Cover image uploaded: ' . $book->cover_image_path);
            }

            $book->save();

            $agent = new Agent();
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'created_book',
                'description' => 'Created book: ' . $book->title,
                'model_type' => Book::class,
                'model_id' => $book->id,
                'data' => json_encode([
                    'title' => $book->title,
                    'author' => $book->author,
                    'isbn' => $book->isbn,
                    'category_id' => $book->category_id,
                    'cover_image_path' => $book->cover_image_path,
                    'file_path' => $book->file_path,
                ]),
                'ip_address' => $request->ip(),
                'platform' => $agent->platform() ?? 'Unknown',
                'browser' => $agent->browser() ?? 'Unknown',
            ]);

            return redirect()->route('admin.books.index')->with('success', 'Book added successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error storing book: ' . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error storing book: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add book: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Book $book)
    {
        try {
            $categories = Category::orderBy('name')->get();

            // Log file paths to debug visibility issues
            Log::info('Editing book: ' . $book->title, [
                'book_id' => $book->id,
                'file_path' => $book->file_path,
                'file_exists' => $book->file_path ? Storage::disk('public')->exists($book->file_path) : false,
                'cover_image_path' => $book->cover_image_path,
                'cover_exists' => $book->cover_image_path ? Storage::disk('public')->exists($book->cover_image_path) : false,
            ]);

            return view('admin.books.edit', compact('book', 'categories'));
        } catch (\Exception $e) {
            Log::error('Error loading edit form for book: ' . $book->title . ' | Error: ' . $e->getMessage());
            return redirect()->route('admin.books.index')->with('error', 'Failed to load edit form: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Book $book)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'isbn' => 'required|string|max:13|unique:books,isbn,' . $book->id,
                'category_id' => 'required|exists:categories,id',
                'description' => 'nullable|string',
                'book_file' => 'nullable|file|mimes:pdf,txt,epub|max:10000', // Updated to allowоси allow .pdf, .txt, .epub
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            ]);

            // Log current state before update
            Log::info('Updating book: ' . $book->title, [
                'book_id' => $book->id,
                'current_file_path' => $book->file_path,
                'current_cover_image_path' => $book->cover_image_path,
            ]);

            if ($request->hasFile('book_file')) {
                if ($book->file_path && Storage::disk('public')->exists($book->file_path)) {
                    Storage::disk('public')->delete($book->file_path);
                    Log::info('Deleted old book file: ' . $book->file_path);
                } elseif ($book->file_path) {
                    Log::warning('Old book file not found or invalid: ' . $book->file_path);
                }
                $validated['file_path'] = $request->file('book_file')->store('books', 'public');
                Log::info('Book file updated: ' . $validated['file_path']);
            } else {
                $validated['file_path'] = $book->file_path;
            }

            if ($request->hasFile('cover_image')) {
                if ($book->cover_image_path && Storage::disk('public')->exists($book->cover_image_path)) {
                    Storage::disk('public')->delete($book->cover_image_path);
                    Log::info('Deleted old cover image: ' . $book->cover_image_path);
                } elseif ($book->cover_image_path) {
                    Log::warning('Old cover image not found or invalid: ' . $book->cover_image_path);
                }
                $validated['cover_image_path'] = $request->file('cover_image')->store('book_covers', 'public');
                Log::info('Cover image updated: ' . $validated['cover_image_path']);
            } else {
                $validated['cover_image_path'] = $book->cover_image_path;
            }

            $book->update($validated);

            $agent = new Agent();
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'updated_book',
                'description' => 'Updated book: ' . $book->title,
                'model_type' => Book::class,
                'model_id' => $book->id,
                'data' => json_encode([
                    'title' => $book->title,
                    'author' => $book->author,
                    'isbn' => $book->isbn,
                    'category_id' => $book->category_id,
                    'cover_image_path' => $book->cover_image_path,
                    'file_path' => $book->file_path,
                ]),
                'ip_address' => $request->ip(),
                'platform' => $agent->platform() ?? 'Unknown',
                'browser' => $agent->browser() ?? 'Unknown',
            ]);

            return redirect()->route('admin.books.index')->with('success', 'Book updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error updating book: ' . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating book: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update book: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Book $book)
    {
        try {
            if ($book->file_path && Storage::disk('public')->exists($book->file_path)) {
                Storage::disk('public')->delete($book->file_path);
                Log::info('Deleted book file: ' . $book->file_path);
            }
            if ($book->cover_image_path && Storage::disk('public')->exists($book->cover_image_path)) {
                Storage::disk('public')->delete($book->cover_image_path);
                Log::info('Deleted cover image: ' . $book->cover_image_path);
            }

            $bookTitle = $book->title;
            $book->delete();

            $agent = new Agent();
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'deleted_book',
                'description' => 'Deleted book: ' . $bookTitle,
                'model_type' => Book::class,
                'model_id' => $book->id,
                'data' => json_encode([
                    'title' => $bookTitle,
                    'author' => $book->author,
                    'isbn' => $book->isbn,
                    'category_id' => $book->category_id,
                    'cover_image_path' => $book->cover_image_path,
                    'file_path' => $book->file_path,
                ]),
                'ip_address' => request()->ip(),
                'platform' => $agent->platform() ?? 'Unknown',
                'browser' => $agent->browser() ?? 'Unknown',
            ]);

            return redirect()->route('admin.books.index')->with('success', 'Book deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting book: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete book: ' . $e->getMessage());
        }
    }

    public function view(Book $book)
    {
        try {
            $book->load('category');
            $categories = Category::orderBy('name')->get();

            $agent = new Agent();
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'viewed_book',
                'description' => 'Viewed book: ' . $book->title,
                'model_type' => Book::class,
                'model_id' => $book->id,
                'data' => json_encode([
                    'title' => $book->title,
                    'author' => $book->author,
                    'isbn' => $book->isbn,
                    'category_id' => $book->category_id,
                    'cover_image_path' => $book->cover_image_path,
                    'file_path' => $book->file_path,
                ]),
                'ip_address' => request()->ip(),
                'platform' => $agent->platform() ?? 'Unknown',
                'browser' => $agent->browser() ?? 'Unknown',
            ]);

            return view('admin.books.view', compact('book', 'categories'));
        } catch (\Exception $e) {
            Log::error('Error viewing book: ' . $e->getMessage());
            return redirect()->route('admin.books.index')->with('error', 'Failed to view book: ' . $e->getMessage());
        }
    }

    public function downloadPdf()
    {
        $books = Book::with('category')->get();
        $pdf = Pdf::loadView('admin.books.pdf', compact('books'));
        return $pdf->download('books-list.pdf');
    }

    public function show($id)
    {
        $book = Book::with('category')->findOrFail($id);
        return view('admin.books.show', compact('book'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,csv|max:2048',
        ]);

        try {
            Excel::import(new BooksImport, $request->file('excel_file'));
            Log::info('Books imported successfully from file: ' . $request->file('excel_file')->getClientOriginalName());
            return redirect()->route('admin.books.index')->with('success', 'Books imported successfully.');
        } catch (\Exception $e) {
            Log::error('Error importing books: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error importing books: ' . $e->getMessage());
        }
    }

    public function borrowed()
    {
        $borrows = Borrow::with(['book', 'user'])
            ->latest('borrowed_at')
            ->get();

        Log::info('Borrowed books fetched', ['count' => $borrows->count()]);

        return view('admin.books.borrowed', compact('borrows'));
    }

    public function issued()
    {
        $borrows = Borrow::with(['book', 'user'])
            ->whereNull('returned_at')
            ->latest('borrowed_at')
            ->get();

        Log::info('Issued books fetched', ['count' => $borrows->count()]);

        return view('admin.books.issued', compact('borrows'));
    }

    public function overdue()
    {
        $borrows = Borrow::with(['book', 'user'])
            ->whereNull('returned_at')
            ->where('due_at', '<', Carbon::today())
            ->latest('borrowed_at')
            ->get();

        Log::info('Overdue books fetched', ['count' => $borrows->count()]);

        return view('admin.books.overdue', compact('borrows'));
    }
}