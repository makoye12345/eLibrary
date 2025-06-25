<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $filter = $request->query('filter', 'name');

        $query = Category::with('parent')->withCount('books');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $query->orderBy($filter === 'books' ? 'books_count' : 'name', 'asc');

        $categories = $query->paginate(10);
        return view('admin.categories.index', compact('categories', 'search', 'filter'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'nullable|exists:categories,id',
                'description' => 'nullable|string',
            ]);

            $category = Category::create($validated);

            $agent = new Agent();
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'created_category',
                'description' => 'Created category: ' . $category->name,
                'model_type' => Category::class,
                'model_id' => $category->id,
                'data' => json_encode([
                    'name' => $category->name,
                    'category_id' => $category->category_id,
                    'description' => $category->description,
                ]),
                'ip_address' => $request->ip(),
                'platform' => $agent->platform() ?? 'Unknown',
                'browser' => $agent->browser() ?? 'Unknown',
            ]);

            return redirect()->route('admin.categories.index')->with('success', 'Category added successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error storing category: ' . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error storing category: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add category: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'nullable|exists:categories,id|not_in:'.$category->id,
                'description' => 'nullable|string',
            ]);

            // Prevent circular references
            if ($validated['category_id']) {
                $parent = Category::find($validated['category_id']);
                if ($parent && $parent->isDescendantOf($category)) {
                    return redirect()->back()->with('error', 'Cannot set a child category as parent to avoid circular reference.')->withInput();
                }
            }

            $category->update($validated);

            $agent = new Agent();
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'updated_category',
                'description' => 'Updated category: ' . $category->name,
                'model_type' => Category::class,
                'model_id' => $category->id,
                'data' => json_encode([
                    'name' => $category->name,
                    'category_id' => $category->category_id,
                    'description' => $category->description,
                ]),
                'ip_address' => $request->ip(),
                'platform' => $agent->platform() ?? 'Unknown',
                'browser' => $agent->browser() ?? 'Unknown',
            ]);

            return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error updating category: ' . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating category: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update category: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Category $category)
    {
        try {
            $categoryName = $category->name;
            $category->delete();

            $agent = new Agent();
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'deleted_category',
                'description' => 'Deleted category: ' . $categoryName,
                'model_type' => Category::class,
                'model_id' => $category->id,
                'data' => json_encode([
                    'name' => $categoryName,
                    'category_id' => $category->category_id,
                    'description' => $category->description,
                ]),
                'ip_address' => request()->ip(),
                'platform' => $agent->platform() ?? 'Unknown',
                'browser' => $agent->browser() ?? 'Unknown',
            ]);

            return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting category: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete category: ' . $e->getMessage());
        }
    }
}