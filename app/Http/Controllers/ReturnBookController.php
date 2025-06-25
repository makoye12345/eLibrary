<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrow;

class ReturnBookController extends Controller
{
    public function index()
    {
        $borrowedBooks = Borrow::where('status', 'borrowed')->get();
        return view('admin.returns.index', compact('borrowedBooks'));
    }

    public function update(Request $request, $id)
    {
        $borrow = Borrow::findOrFail($id);
        $borrow->status = 'returned';
        $borrow->returned_at = now();
        $borrow->save();

        return redirect()->back()->with('success', 'Book returned successfully.');
    }
}
