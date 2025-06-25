<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Fine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentsController extends Controller
{
    public function __construct()
    {
       // $this->middleware(['auth']);
    }

    public function index()
    {
        $invoices = Invoice::with('user')->latest()->get();
        return view('admin.payments.index', compact('invoices'));
    }

    public function show($id)
    {
        $invoice = Invoice::with(['user', 'fines.borrow.book'])->findOrFail($id);
        return view('admin.payments.show', compact('invoice'));
    }

    public function markAsPaid(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        
        // Validate the payment amount
        $request->validate([
            'paid_amount' => 'required|numeric|min:0|max:' . $invoice->balance,
        ]);

        $paidAmount = $request->input('paid_amount');
        $invoice->paid_amount += $paidAmount;
        $invoice->balance -= $paidAmount;
        
        // If fully paid, mark associated fines as paid
        if ($invoice->balance <= 0) {
            $fines = Fine::where('user_id', $invoice->user_id)
                ->where('is_paid', 0)
                ->whereIn('id', function ($query) use ($invoice) {
                    $query->select('fine_id')
                        ->from('borrow_fine')
                        ->whereIn('borrow_id', function ($subQuery) use ($invoice) {
                            $subQuery->select('id')
                                ->from('borrows')
                                ->where('user_id', $invoice->user_id)
                                ->where('due_at', '<', now())
                                ->whereNull('returned_at');
                        });
                })->get();

            foreach ($fines as $fine) {
                $fine->is_paid = true;
                $fine->save();
            }
        }

        $invoice->save();

        return redirect()->route('admin.payments.index')->with('success', 'Payment updated successfully.');
    }
}