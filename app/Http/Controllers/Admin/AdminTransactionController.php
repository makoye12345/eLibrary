<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminTransactionController extends Controller
{
   
    public function index()
    {
        $transactions = Transaction::with('user', 'book')
            ->orderBy('transaction_date', 'desc')
            ->paginate(10);

        return view('admin.transactions.index', compact('transactions'));
    }
}