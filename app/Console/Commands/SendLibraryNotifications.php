<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Reservation;
use App\Notifications\LibraryNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendLibraryNotifications extends Command
{
    protected $signature = 'library:notify';
    protected $description = 'Send notifications for library events';

    public function handle()
    {
        $now = Carbon::now();

        // Due dates approaching (within 2 days)
        $borrowsDueSoon = Borrow::whereBetween('due_date', [$now, $now->copy()->addDays(2)])->get();
        foreach ($borrowsDueSoon as $borrow) {
            $borrow->user->notify(new LibraryNotification(
                'due_soon',
                $borrow->book->title,
                ['due_date' => $borrow->due_date->format('Y-m-d')]
            ));
        }

        // Overdue books
        $overdueBorrows = Borrow::where('due_date', '<', $now)->get();
        foreach ($overdueBorrows as $borrow) {
            $borrow->user->notify(new LibraryNotification(
                'overdue',
                $borrow->book->title,
                ['due_date' => $borrow->due_date->format('Y-m-d')]
            ));
        }

        // New books (added within 3 days)
        $newBooks = Book::where('added_date', '>=', $now->copy()->subDays(3))->get();
        foreach ($newBooks as $book) {
            foreach (\App\Models\User::all() as $user) {
                $user->notify(new LibraryNotification('new_book', $book->title));
            }
        }

        // Reserved books ready (assuming books become available when no longer borrowed)
        $reservations = Reservation::whereIn('book_id', Book::whereDoesntHave('borrows')->pluck('id'))->get();
        foreach ($reservations as $reservation) {
            $reservation->user->notify(new LibraryNotification('reservation_ready', $reservation->book->title));
            $reservation->delete(); // Clear reservation
        }

        $this->info('Library notifications sent successfully.');
    }
}