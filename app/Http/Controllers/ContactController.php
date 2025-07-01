<?php
namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        Log::info('Contact form submission received:', $request->all());

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|min:3|max:255',
            'message' => 'required|string|min:10|max:2000',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed for contact form:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $message = Message::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'subject' => $request->input('subject'),
                'message' => $request->input('message'),
                'sender_id' => auth()->check() ? auth()->id() : null,
                'recipient_id' => null,
                'is_broadcast' => false,
            ]);

            Log::info('Contact message saved successfully:', ['id' => $message->id]);

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving contact message: ' . $e->getMessage(), [
                'data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to save message. Please try again.'
            ], 500);
        }
    }
}