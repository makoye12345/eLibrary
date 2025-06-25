<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Borrow extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'book_id',
        'status',
        'borrowed_at',
        'due_at',
        'renewal_count',
        'returned_at',
        'remarks',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'borrowed_at'    => 'datetime',
        'due_at'         => 'datetime',
        'returned_at'    => 'datetime',
        'renewal_count'  => 'integer',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = ['is_overdue'];

    /**
     * Relationship: User who borrowed the book.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Deleted User',
            'email' => 'deleted@example.com',
        ]);
    }

    /**
     * Relationship: Book that was borrowed.
     *
     * @return BelongsTo
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class)->withDefault([
            'title' => 'Deleted Book',
            'isbn' => 'N/A',
        ]);
    }

    /**
     * Relationship: Fine associated with this borrow.
     *
     * @return HasOne
     */
    public function fine(): HasOne
    {
        return $this->hasOne(Fine::class);
    }

    /**
     * Accessor: Check if the book is overdue.
     *
     * @return bool
     */
    public function getIsOverdueAttribute(): bool
    {
        if ($this->returned_at !== null) {
            return false; // Not overdue if already returned
        }
        return $this->due_at < now();
    }

    /**
     * Calculate overdue days.
     *
     * @return int
     */
    public function overdueDays(): int
    {
        $endDate = $this->returned_at ?? now();
        if ($endDate > $this->due_at) {
            return $this->due_at->diffInDays($endDate);
        }
        return 0;
    }

    /**
     * Calculate fine amount (TSh 2,000 per overdue day).
     *
     * @return float
     */
    public function calculateFine(): float
    {
        return $this->overdueDays() * 2000;
    }

    /**
     * Scope: Get active borrows (not yet returned).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereNull('returned_at')
                    ->where('status', '!=', 'returned');
    }

    /**
     * Scope: Get returned borrows.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReturned($query)
    {
        return $query->whereNotNull('returned_at')
                    ->where('status', 'returned');
    }

    /**
     * Scope: Get current borrows for a specific user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentBorrows($query, $userId)
    {
        return $query->where('user_id', $userId)
                    ->active();
    }

    /**
     * Scope: Get overdue borrows.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOverdue($query)
    {
        return $query->active()
                    ->where('due_at', '<', now());
    }

    
}