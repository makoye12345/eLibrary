<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LibraryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $type;
    private $bookTitle;
    private $details;

    public function __construct(string $type, string $bookTitle, array $details = [])
    {
        $this->type = $type;
        $this->bookTitle = $bookTitle;
        $this->details = $details;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject($this->getSubject())
            ->greeting('Hello ' . $notifiable->name . ',');

        switch ($this->type) {
            case 'due_soon':
                $mail->line("The book '{$this->bookTitle}' is due on {$this->details['due_date']}.")
                     ->line('Please return it on time.');
                break;
            case 'overdue':
                $mail->line("The book '{$this->bookTitle}' was due on {$this->details['due_date']}.")
                     ->line('Please return it as soon as possible.');
                break;
            case 'new_book':
                $mail->line("A new book '{$this->bookTitle}' has been added to the library.")
                     ->line('Check it out!');
                break;
            case 'reservation_ready':
                $mail->line("The book '{$this->bookTitle}' you reserved is now available for pickup.")
                     ->line('Please collect it within 3 days.');
                break;
        }

        return $mail->action('View Library', url('/library'))
                    ->line('Thank you,');
    }

    private function getSubject(): string
    {
        return match ($this->type) {
            'due_soon' => "Reminder: {$this->bookTitle} Due Soon",
            'overdue' => "Overdue: {$this->bookTitle}",
            'new_book' => "New Book Added: {$this->bookTitle}",
            'reservation_ready' => "Reserved Book Ready: {$this->bookTitle}",
            default => 'Library Notification',
        };
    }
}