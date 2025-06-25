<?php

namespace App\Http\Controllers;

use App\Events\NotificationCreated;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::latest()->get();
        return response()->json(['notifications' => $notifications]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'action' => 'required|in:borrow,return,reserve,payment',
            'user_id' => 'required|exists:users,id',
            'details' => 'nullable|array',
        ]);

        $user = User::find($request->user_id);
        $message = match ($request->action) {
            'borrow' => "User {$user->name} borrowed '{$request->details['book_title']}'",
            'return' => "User {$user->name} returned '{$request->details['book_title']}'",
            'reserve' => "User {$user->name} reserved '{$request->details['book_title']}'",
            'payment' => "User {$user->name} made a payment of {$request->details['amount']}",
            default => 'Unknown action',
        };

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'action' => $request->action,
            'details' => $request->details,
            'message' => $message,
        ]);

        event(new NotificationCreated($notification));

        return response()->json($notification, 201);
    }
}