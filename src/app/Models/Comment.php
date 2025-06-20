<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'listing_id',
        'user_id',
        'comment',
    ];

    // Relations
    public function user() { return $this->belongsTo(User::class); }
    public function listing() { return $this->belongsTo(Listing::class); }
}
