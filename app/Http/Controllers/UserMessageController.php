<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserMessageController extends Controller
{
    /**
     * Constructor for authentication middleware.
     * Ensures only authenticated users can access these methods.
     */
    public function __construct()
    {
        $this->middleware('auth'); // Hii inahakikisha mtumiaji ame-login kabla ya kufikia methods hizi
    }

    /**
     * Display user's messages and compose form.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // Hakikisha mtumiaji ame-login. Middleware ya 'auth' inasaidia,
        // lakini ukaguzi wa wazi ni mzuri kwa debugging au kwa routes zisizo na middleware.
        if (!Auth::check()) {
            Log::warning('Unauthenticated user attempted to access user messages index.');
            return redirect()->route('login')->with('error', 'Tafadhali ingia kwanza kufikia ujumbe wako.');
        }

        $user = Auth::user(); // Sasa $user haitakuwa null kama Auth::check() ni true
        Log::info('Accessing user messages index page.', ['user_id' => $user->id]);

        try {
            // Pata ujumbe kwa mtumiaji wa sasa
            // Message::forUser($user->id) inategemea scope kwenye Message model.
            // Hakikisha scope hii inajumuisha ujumbe uliotumwa NA kupokelewa, na ujumbe wa broadcast.
            $messages = Message::where('sender_id', $user->id)
                                ->orWhere('recipient_id', $user->id)
                                // Kwa ujumbe wa broadcast, watumiaji wote wana record zao.
                                // Sasa kwa kuwa admin anarekodi ujumbe mmoja mmoja kwa kila user
                                // wakati wa broadcast, hatuhitaji logiki maalum hapa kwa broadcast.
                                ->with(['sender', 'recipient'])
                                ->latest()
                                ->get();

            // Piki watumiaji wengine wote isipokuwa mtumiaji wa sasa
            $users = User::where('id', '!=', $user->id)->get();

            return view('user.messages.index', compact('messages', 'users'));
        } catch (\Exception $e) {
            Log::error('Error loading user messages index: ' . $e->getMessage(), [
                'user_id' => $user->id ?? 'N/A', // Tumia null coalescing operator kwa usalama
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Kuna tatizo wakati wa kupakia ukurasa wa ujumbe. Tafadhali jaribu tena.');
        }
    }

    /**
     * Store a new message or reply.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            Log::warning('Unauthenticated user attempted to store a message.');
            return redirect()->route('login')->with('error', 'Tafadhali ingia kwanza kutuma ujumbe.');
        }

        Log::info('User message submission received:', $request->all(), ['sender_id' => Auth::id()]);

        $validator = Validator::make($request->all(), [
            'recipient_id' => 'required|exists:users,id',
            'message' => 'required|string|min:10|max:2000',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed for user message:', $validator->errors()->toArray(), ['sender_id' => Auth::id()]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $message = Message::create([
                'sender_id' => Auth::id(),
                'recipient_id' => $request->recipient_id,
                'message' => $request->message,
                'is_broadcast' => false, // Ujumbe wa mtumiaji daima si broadcast
                'name' => null,
                'email' => null,
                'subject' => null,
            ]);

            Log::info('User message saved successfully:', ['id' => $message->id, 'sender_id' => Auth::id(), 'recipient_id' => $request->recipient_id]);
            return redirect()->back()->with('success', 'Ujumbe umetumwa kwa mafanikio!');
        } catch (\Exception $e) {
            Log::error('Error saving user message: ' . $e->getMessage(), [
                'data' => $request->all(),
                'sender_id' => Auth::id() ?? 'N/A',
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Kuna tatizo limetokea wakati wa kutuma ujumbe. Tafadhali jaribu tena.');
        }
    }

    /**
     * Mark a message as read.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function read($id)
    {
        if (!Auth::check()) {
            Log::warning('Unauthenticated user attempted to mark message as read.');
            return redirect()->route('login')->with('error', 'Tafadhali ingia kwanza.');
        }

        try {
            // Hakikisha mtumiaji wa sasa ndiye mpokeaji wa ujumbe huu
            $message = Message::where('recipient_id', Auth::id())->findOrFail($id);
            $message->markAsRead(); // Hii inahitaji method 'markAsRead' kwenye Message model

            Log::info('Message marked as read:', ['id' => $id, 'user_id' => Auth::id()]);
            return redirect()->back()->with('success', 'Ujumbe umewekwa alama kuwa umesomwa.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Attempted to mark non-existent or unauthorized message as read.', ['message_id' => $id, 'user_id' => Auth::id()]);
            return redirect()->back()->with('error', 'Ujumbe haukuweza kupatikana au hauruhusiwi kuusoma.');
        } catch (\Exception $e) {
            Log::error('Error marking message as read: ' . $e->getMessage(), ['id' => $id, 'user_id' => Auth::id(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Kuna tatizo wakati wa kuweka alama ya kusomwa.');
        }
    }
}