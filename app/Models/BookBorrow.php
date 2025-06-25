<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookBorrow extends Model
{
    use HasFactory;

    // If your database table is named something other than 'book_borrows',
    // specify it here, for example:
    // protected $table = 'borrowings';

    // Define which columns can be mass-assigned (update/create)
    protected $fillable = [
        'user_id',
        'book_id',
        'borrowed_at',
        'due_date',
        'returned_at',
        'status', // e.g., borrowed, returned, overdue
        'fine_amount',
    ];

    // If your timestamps are not standard Laravel ones, you can disable or customize:
    // public $timestamps = false;

    /**
     * Relationship: BookBorrow belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: BookBorrow belongs to a Book
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
