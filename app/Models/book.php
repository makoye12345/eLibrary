<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Carbon\Carbon;

class Book extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'author',
        'publisher_id',
        'category_id',
        'isbn',
        'description',
        'published_at',
        'file_path',
        'cover_image_path',
        'status',
        'is_available',
        'is_purchasable',
        'price',
        'rental_price',
        'is_lost',
        'barcode',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'published_at' => 'date',
        'is_available' => 'boolean',
        'is_purchasable' => 'boolean',
        'price' => 'decimal:2',
        'rental_price' => 'decimal:2',
        'deleted_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['reading_url'];

    /**
     * Get the reading URL for the book.
     *
     * @return string
     */
   public function getReadingUrlAttribute()
{
    return $this->id ? route('user.books.read', ['id' => $this->id]) : '#';
}


    /**
     * Relationships
     */
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function loans()
    {
        return $this->hasMany(BookLoan::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function fines()
    {
        return $this->hasMany(Fine::class);
    }

    /**
     * Get only active borrows (not returned).
     */
    public function activeBorrows()
    {
        return $this->hasMany(Borrow::class)->whereNull('returned_at');
    }

    /**
     * Check if the book is currently borrowed by a specific user.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function isBorrowedBy($user)
    {
        return $this->activeBorrows()
                    ->where('user_id', $user->id)
                    ->exists();
    }

    /**
     * Calculate fine for a specific borrow.
     *
     * @param \App\Models\Borrow $borrow
     * @return float
     */
    public function calculateFine($borrow)
    {
        if ($this->is_lost) {
            return 10000.00; // Fixed fine for lost book
        }

        if ($borrow->returned_at) {
            if ($borrow->returned_at > $borrow->due_date) {
                $days_overdue = $borrow->returned_at->diffInDays($borrow->due_date);
                return $days_overdue * 1000.00;
            }
            return 0.00;
        }

        $today = Carbon::now();
        if ($today > $borrow->due_date) {
            $days_overdue = $today->diffInDays($borrow->due_date);
            return $days_overdue * 1000.00;
        }

        return 0.00;
    }

    /**
     * Scope for available books.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
                     ->where('status', 'available');
    }

    /**
     * Scope for purchasable books.
     */
    public function scopePurchasable($query)
    {
        return $query->where('is_purchasable', true)
                     ->where('price', '>', 0);
    }
}