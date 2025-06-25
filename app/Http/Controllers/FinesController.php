<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\Fine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinesController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $allBorrows = Borrow::where('user_id', $user->id)
            ->with('book.publisher', 'book.category')
            ->get();

        return view('user.fines.index', compact('allBorrows'));
    }

    public function payFine(Request $request)
    {
        $request->validate([
            'fine_id' => 'required|exists:fines,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:mpesa,halopesa,nmb,card',
            'phone' => 'required_if:payment_method,mpesa,halopesa|nullable',
            'card_number' => 'required_if:payment_method,card,nmb|nullable',
            'expiry' => 'required_if:payment_method,card|nullable',
            'cvv' => 'required_if:payment_method,card|nullable',
        ]);

        $fine = Fine::find($request->fine_id);
        if ($fine->user_id !== Auth::id() || $fine->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Invalid fine.']);
        }

        // Mock payment processing
        $paymentDetails = [
            'fine_id' => $fine->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'phone' => $request->phone,
            'card_number' => $request->card_number ? substr($request->card_number, -4) : null,
            'status' => 'success',
        ];

        $fine->update(['status' => 'paid']);
        session()->flash('success', 'Payment successful!');

        return response()->json(['success' => true]);
    }
}