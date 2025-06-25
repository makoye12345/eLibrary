<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // hakikisha ume-import DB
use App\Notifications\AdminMessage; // hakikisha ume-import notification kama unaitumia

class AdminMessageController extends Controller
{
    public function index()
    {
        $userId = auth()->id(); // Pata user aliyeingia
        $users = User::orderBy('name')->get();

        $messages = Message::with(['sender', 'recipient'])
            ->where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->orWhere('recipient_id', $userId)
                      ->orWhere('is_broadcast', true);
            })
            ->latest()
            ->get();

        return view('admin.messages.index', compact('users', 'messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'is_broadcast' => 'nullable|boolean',
            'recipient_id' => 'required_if:is_broadcast,false|exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            $messageData = [
                'sender_id' => Auth::id(),
                'content' => $request->content,
                'is_broadcast' => $request->has('is_broadcast') ? $request->is_broadcast : false,
                'recipient_id' => $request->is_broadcast ? null : $request->recipient_id,
            ];

            $message = Message::create($messageData);

            // Notification logic (optional)
            if (!$message->is_broadcast && $message->recipient_id) {
                $user = User::find($message->recipient_id);
                if ($user) {
                    $user->notify(new AdminMessage($message->content));
                }
            } elseif ($message->is_broadcast) {
                $users = User::where('role', 'member')->get();
                foreach ($users as $user) {
                    $user->notify(new AdminMessage($message->content));
                }
            }

            DB::commit();
            return redirect()->route('admin.messages.index')->with('success', 'Message sent successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.messages.index')->with('error', 'Failed to send message: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $message = Message::where('id', $id)
            ->where('sender_id', Auth::id())
            ->firstOrFail();

        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Message deleted successfully.');
    }
}
