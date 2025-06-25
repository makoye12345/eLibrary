<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'photo',
    ];

    /**
     * Relationship: Get the user that owns the profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor: Get the full URL to the user's photo.
     */
    public function getPhotoUrlAttribute()
    {
        return $this->photo 
            ? Storage::url('photos/' . $this->photo) 
            : asset('default-profile.png');
    }

    /**
     * Mutator: Automatically store the photo name when a file is uploaded.
     */
    public function setPhotoAttribute($value)
    {
        if (is_file($value)) {
            $this->attributes['photo'] = $value->store('photos', 'public');
        } else {
            $this->attributes['photo'] = $value;
        }
    }
}
