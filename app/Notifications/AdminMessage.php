<?php

namespace App\Notifications;

use App\Models\Message;

use Illuminate\Bus\Queueable;

use Illuminate\Notifications\Notification;

class AdminMessage extends Notification
{
    use Queueable;

    protected $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function via($notifiable)    {
        return ['database'];
    }

    public function toDatabase($notifiable)    {
        return [
            'message_id' => $this->message->id,
            'message' => $this->message->message,
            'sender_id' => $this->message->user_id,
            'sender_name' => $this->message->user->name ?? 'Admin',
            'created_at' => $this->message->created_at->toDateTimeString(),
        ];
    }
}