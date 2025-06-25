<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * List all categories with search, filter, and pagination.
     */
    public function index(Request $request)
    {
        // Retrieve search and filter parameters from the query string
        $search = $request->query('search');
        $filter = $request->query('filter', 'name');

        // Build the query for categories, selecting all category fields
        $query = Category::select('categories.*')
            ->leftJoin('books', 'categories.id', '=', 'books.category_id')
            ->groupBy('categories.id');

        // Apply search filter if provided
        if ($search) {
            $query->where('categories.name', 'like', '%' . $search . '%')
                  ->orWhere('categories.description', 'like', '%' . $search . '%'); // Added description search
        }

        // Apply sorting based on filter
        if ($filter === 'books') {
            $query->orderByRaw('COUNT(books.id) DESC');
        } else {
            $query->orderBy('categories.name');
        }

        // Paginate results with 10 items per page, including books count
        $categories = $query->withCount('books')->paginate(10);

        // Pass categories, search, and filter to the view
        return view('admin.categories.index', compact('categories', 'search', 'filter'));
    }

    /**
     * Show the form to create a new category.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a new category in the database.
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|unique:categories|max:255',
            'description' => 'nullable|string',
        ]);

        // Create the category
        Category::create($validated);

        // Redirect with success message
        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully!');
    }

    /**
     * Show the form to edit an existing category.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update an existing category.
     */
    public function update(Request $request, Category $category)
    {
        // Validate input, allowing the current category's name
        $validated = $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        // Update the category
        $category->update($validated);

        // Redirect with success message
        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Delete a category, if it has no associated books.
     */
    public function destroy(Category $category)
    {
        // Check if the category has associated books
        if ($category->books()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Cannot delete category with associated books!');
        }

        // Delete the category
        $category->delete();

        // Redirect with success message
        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully!');
    }

    /**
     * Show books belonging to a specific category.
     */
    public function showBooks(Category $category)
    {
        // Load books for the category
        $books = $category->books()->get();
        return view('admin.categories.books', compact('category', 'books'));
    }
}