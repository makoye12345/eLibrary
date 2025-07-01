<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    /**
     * Display the admin messaging interface with compose form and message list.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Log::info('Accessing admin messages index page.');

        try {
            $users = User::all();

            // Pata ujumbe wote wa admin
            // Kwanza, pata ujumbe wa kawaida uliotumwa na admin au kupokelewa na admin.
            $userSpecificMessages = Message::with(['sender', 'recipient'])
                                        ->where('sender_id', auth()->id())
                                        ->where('is_broadcast', false) // Ujumbe maalum alioutuma admin
                                        ->orWhere(function($query) {
                                            $query->where('recipient_id', auth()->id())
                                                  ->where('is_broadcast', false); // Ujumbe maalum alioupokea admin
                                        })
                                        ->get();

            // Pili, pata ujumbe wa broadcast (ambao admin amewahi kutuma)
            // Tutatafuta ujumbe wowote wa broadcast ambao 'sender_id' ni ya admin.
            // Na tutachukua 'DISTINCT' (unique) message_id kwa kila ujumbe wa broadcast
            // ili ujumbe mmoja tu wa broadcast uonekane kwenye orodha ya admin.
            $broadcastMessages = Message::with(['sender']) // Hatuhitaji recipient kwa broadcast hapa
                                        ->where('sender_id', auth()->id())
                                        ->where('is_broadcast', true)
                                        ->select('message', 'sender_id', 'is_broadcast', 'created_at') // Chagua columns zinazohitajika
                                        ->distinct('message') // Hii itachukua ujumbe wa kipekee kwa text yake
                                        ->get();

            // Unganisha ujumbe wa kawaida na broadcast messages.
            // Ni muhimu kuondoa marudio kama ujumbe wa broadcast ulirekodiwa mara nyingi.
            // Njia rahisi ni kubadilisha broadcast messages kuwa 'pseudo-messages' za kuonyesha.
            $combinedMessages = collect();

            // Ongeza ujumbe maalum
            foreach ($userSpecificMessages as $msg) {
                $combinedMessages->push($msg);
            }

            // Ongeza ujumbe wa broadcast kama "single entry"
            foreach ($broadcastMessages as $bMsg) {
                // Unda object mpya inayofanana na Message model
                $pseudoMessage = new Message();
                $pseudoMessage->id = 'broadcast_' . md5($bMsg->message . $bMsg->created_at); // ID bandia ya kipekee
                $pseudoMessage->sender_id = $bMsg->sender_id;
                $pseudoMessage->recipient_id = null; // Kwa sababu ni broadcast
                $pseudoMessage->message = $bMsg->message;
                $pseudoMessage->is_broadcast = true;
                $pseudoMessage->created_at = $bMsg->created_at;
                $pseudoMessage->sender = $bMsg->sender; // Hakikisha sender anaonekana

                $combinedMessages->push($pseudoMessage);
            }

            // Panga ujumbe wote kwa muda mpya zaidi
            $messages = $combinedMessages->sortByDesc('created_at');


            return view('admin.messages.index', compact('users', 'messages'));
        } catch (\Exception $e) {
            Log::error('Error loading admin messages index: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Kuna tatizo wakati wa kupakia ukurasa wa ujumbe. Tafadhali jaribu tena.');
        }
    }

    /**
     * Store a new message (user-specific or broadcast to all users).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Log::info('Admin message submission received:', $request->all());

        $validator = Validator::make($request->all(), [
            'message' => 'required|string|min:10|max:2000',
            'recipient_id' => 'required_without:is_broadcast|nullable|exists:users,id',
            'is_broadcast' => 'nullable|in:1,on',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed for admin message:', $validator->errors()->toArray());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $isBroadcast = $request->boolean('is_broadcast');

            if (!auth()->check()) {
                Log::error('Unauthorized attempt to send admin message: User not authenticated.');
                return redirect()->back()->with('error', 'Inabidi uwe umeingia kutuma ujumbe.');
            }
            $senderId = auth()->id();

            if ($isBroadcast) {
                // *** HAPA TUNAREJESHA LOGIKI YA KUTUMA UJUMBE KWA KILA MTUMIAJI ***
                // Hii inahakikisha kila user anapata ujumbe wake mwenyewe.
                $users = User::all();

                if ($users->isEmpty()) {
                    Log::warning('Attempted to broadcast message, but no users found in the database.');
                    return redirect()->back()->with('error', 'Hakuna watumiaji wowote waliosajiliwa wa kuwatumia ujumbe.');
                }

                foreach ($users as $user) {
                    Message::create([
                        'sender_id' => $senderId,
                        'recipient_id' => $user->id, // Muhimu: Sasa tunamjaza recipient_id
                        'message' => $request->message,
                        'is_broadcast' => true, // Bado ni true kuashiria broadcast
                        'name' => null,
                        'email' => null,
                        'subject' => null,
                    ]);
                }
                Log::info('Broadcast messages saved individually for all users.', ['count' => $users->count(), 'sender_id' => $senderId]);
                return redirect()->back()->with('success', 'Ujumbe wa jumla umetumwa kwa watumiaji wote!');

            } else {
                // Ujumbe kwa mtumiaji mmoja maalum (hakuna mabadiliko hapa)
                if (empty($request->recipient_id)) {
                    return redirect()->back()->withErrors(['recipient_id' => 'Tafadhali chagua mpokeaji wa ujumbe.'])->withInput();
                }

                $message = Message::create([
                    'sender_id' => $senderId,
                    'recipient_id' => $request->recipient_id,
                    'message' => $request->message,
                    'is_broadcast' => false,
                    'name' => null,
                    'email' => null,
                    'subject' => null,
                ]);
                Log::info('User-specific message saved successfully.', ['message_id' => $message->id, 'recipient_id' => $request->recipient_id, 'sender_id' => $senderId]);
                return redirect()->back()->with('success', 'Ujumbe umetumwa kwa mafanikio!');
            }
        } catch (\Exception $e) {
            Log::error('Error saving admin message: ' . $e->getMessage(), [
                'data' => $request->all(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->with('error', 'Kuna tatizo limetokea wakati wa kutuma ujumbe. Tafadhali jaribu tena.');
        }
    }

    /**
     * Delete a message.
     *
     * @param int $id The ID of the message to delete.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        Log::info('Attempting to delete message.', ['message_id' => $id]);

        try {
            // Tofautisha kama ni 'pseudo-ID' ya broadcast au ID halisi ya ujumbe
            if (Str::startsWith($id, 'broadcast_')) {
                // Kama ni pseudo-ID, hatuwezi kufuta. Labda tungekuwa na logiki ya kufuta
                // ujumbe wote wa broadcast kwa wakati mmoja, lakini kwa sasa, tuzuie.
                Log::warning('Attempted to delete pseudo broadcast message ID directly from admin view.', ['id' => $id]);
                return redirect()->back()->with('error', 'Ujumbe wa jumla hauwezi kufutwa moja kwa moja kutoka hapa. Futa ujumbe maalum uliotuma.');
            }

            $message = Message::findOrFail($id);
            $message->delete();
            Log::info('Message deleted successfully.', ['message_id' => $id]);
            return redirect()->back()->with('success', 'Ujumbe umefutwa kwa mafanikio!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Attempted to delete non-existent message.', ['message_id' => $id]);
            return redirect()->back()->with('error', 'Ujumbe haukuweza kupatikana.');
        } catch (\Exception $e) {
            Log::error('Error deleting message: ' . $e->getMessage(), [
                'message_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Kuna tatizo limetokea wakati wa kufuta ujumbe. Tafadhali jaribu tena.');
        }
    }
}