<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class BookAvailableNotification extends Notification
{
    use Queueable;

    protected $book;

    public function __construct($book)
    {
        $this->book = $book;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'book_id' => $this->book->id,
            'book_title' => $this->book->title,
            'message' => "The book '{$this->book->title}' you reserved is now available for borrowing!",
        ];
    }
}