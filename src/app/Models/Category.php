<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable =  ['name'];

    // Relations
    public function listings()
    {
        return $this->belongsToMany(Listing::class, 'category_listing');
    }
}
