<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Book;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'status',
        'amount',
        'returned_at',
        'borrowed_at',
        'due_date',
        'created_at',
        'updated_at',
    ];

    /**
     * The user who borrowed the book (borrower).
     */
    public function borrower()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The user model (alias for borrower).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The book associated with the transaction.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
