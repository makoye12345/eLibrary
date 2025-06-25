<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'control_number',
        'user_id',
        'description',
        'invoice_amount',
        'paid_amount',
        'balance',
        'statement',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fines()
    {
        return $this->hasMany(Fine::class, 'user_id', 'user_id');
    }
}