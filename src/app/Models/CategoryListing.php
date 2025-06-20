<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryListing extends Model
{
    protected $table = 'category_listing';

    protected $fillable = [
        'listing_id',
        'category_id',
    ];

    // Relations
    public function listing() { return $this->belongsTo(Listing::class); }
    public function category() { return $this->belongsTo(Category::class); }
}
