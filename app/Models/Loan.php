<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = ['book_id', 'borrower_name', 'issue_date', 'due_date', 'return_date'];

    protected $dates = ['issue_date', 'due_date', 'return_date'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function calculateFine()
    {
        if ($this->book->is_lost) {
            return 10000; // Fixed fine for lost book
        }

        if ($this->return_date) {
            if ($this->return_date > $this->due_date) {
                $days_overdue = $this->return_date->diffInDays($this->due_date);
                return $days_overdue * 1000;
            }
            return 0;
        }

        $today = Carbon::now();
        if ($today > $this->due_date) {
            $days_overdue = $today->diffInDays($this->due_date);
            return $days_overdue * 1000;
        }

        return 0;
    }

    public function daysRemaining()
    {
        $today = Carbon::now();
        if ($this->return_date) {
            return $this->due_date->diffInDays($this->return_date, false); // Negative if overdue
        }
        return $this->due_date->diffInDays($today, false); // Negative if overdue
    }
}