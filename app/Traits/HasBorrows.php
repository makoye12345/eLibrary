<?php

namespace App\Traits;

trait HasBorrows
{
    public function borrows()
    {
        return $this->hasMany(\App\Models\Borrow::class);
    }

    public function hasBorrowed($bookId)
    {
        return $this->borrows()
            ->where('book_id', $bookId)
            ->whereNull('returned_at')
            ->exists();
    }
}