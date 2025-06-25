<?php
// app/Models/BookTransaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookTransaction extends Model
{
    protected $fillable = [
        'user_id', 
        'book_id',
        'borrowed_date',
        'return_date',
        'returned_date',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}