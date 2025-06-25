<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function payFine($id)
    {
        $loan = Loan::findOrFail($id);
        // Add payment logic here (e.g., update loan status or record payment)
        return redirect()->route('reports')->with('success', 'Fine payment processed.');
    }
}