<?php
// app/Http/Controllers/CategoryBookController.php
namespace App\Http\Controllers;

use App\Models\CategoryBook;
use Illuminate\Http\Request;

class CategoryBookController extends Controller
{
    public function index()
    {
        return view('category-books.index', [
            'categories' => CategoryBook::all()
        ]);
    }

    public function create()
    {
        return view('category-books.create');
    }

    // Add other resource methods (store, show, edit, update, destroy)
}