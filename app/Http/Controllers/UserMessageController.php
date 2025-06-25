<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserMessageController extends Controller
{
    public function index()
    {
        // Get all users except current user
        $users = User::where('id', '!=', Auth::id())
                   ->orderBy('name')
                   ->get();

        // Get all relevant messages
        $messages = Message::with(['sender', 'recipient'])
            ->where(function($query) {
                $query->where('sender_id', Auth::id())
                      ->orWhere('recipient_id', Auth::id())
                      ->orWhere('is_broadcast', true);
            })
            ->latest()
            ->paginate(15);

        // Get unread count (cached for 5 minutes)
        $unreadCount = Cache::remember('unread_count_'.Auth::id(), now()->addMinutes(5), function() {
            return Message::where('recipient_id', Auth::id())
                       ->whereNull('read_at')
                       ->count();
        });

        return view('user.messages.index', compact('messages', 'users', 'unreadCount'));
    }

    public function create()
    {
        $users = User::where('id', '!=', Auth::id())
                   ->orderBy('name')
                   ->get();

        return view('user.messages.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $request->recipient_id,
            'content' => $request->content,
        ]);

        return redirect()->route('user.messages.index')
                       ->with('success', 'Message sent successfully!');
    }

    public function show(Message $message)
    {
        // Mark as read if recipient is current user
        if ($message->recipient_id == Auth::id() && !$message->read_at) {
            $message->update(['read_at' => now()]);
            Cache::forget('unread_count_'.Auth::id());
        }

        return view('user.messages.show', compact('message'));
    }

    public function markAsRead(Message $message)
    {
        abort_if($message->recipient_id != Auth::id(), 403);

        if (!$message->read_at) {
            $message->update(['read_at' => now()]);
            Cache::forget('unread_count_'.Auth::id());
        }

        return back()->with('success', 'Message marked as read');
    }

    public function destroy(Message $message)
    {
        abort_unless(
            $message->sender_id == Auth::id() || $message->recipient_id == Auth::id(),
            403
        );

        $message->delete();
        Cache::forget('unread_count_'.Auth::id());

        return back()->with('success', 'Message deleted');
    }
}