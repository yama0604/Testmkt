<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $fillable = [
        'user_id',
        'listing_name',
        'brand_name',
        'price',
        'explanation',
        'status',
        'image',
        'sold_flag',
    ];

    // 出品状態（status）定数
    public const STATUS_GOOD = 0;
    public const STATUS_NO_NOTICEABLE_DAMAGE = 1;
    public const STATUS_SOME_DAMAGE = 2;
    public const STATUS_BAD = 3;

    // 出品状態ラベル
    public const STATUS_LABELS = [
        self::STATUS_GOOD => '良好',
        self::STATUS_NO_NOTICEABLE_DAMAGE => '目立った傷や汚れは無し',
        self::STATUS_SOME_DAMAGE => 'やや傷や汚れあり',
        self::STATUS_BAD => '状態が悪い',
    ];

    // 販売状況（sold_flag）定数
    public const SOLD_FLAG_ON_SALE = 0;
    public const SOLD_FLAG_SOLD_OUT = 1;

    // 販売状況ラベル
    public const SOLD_FLAG_LABELS = [
        self::SOLD_FLAG_ON_SALE => '販売中',
        self::SOLD_FLAG_SOLD_OUT => '売切',
    ];

    // 出品状態ラベル取得用
    public function getStatusLabelAttribute()
    {
        return self::STATUS_LABELS[$this->status];
    }

    // 販売状況ラベル取得用
    public function getSoldFlagLabelAttribute()
    {
        return self::SOLD_FLAG_LABELS[$this->sold_flag];
    }

    // Relations
    public function user() { return $this->belongsTo(User::class); }
    public function purchases() { return $this->hasMany(Purchase::class); }
    public function likes() { return $this->hasMany(Like::class); }
    public function comments() { return $this->hasMany(Comment::class); }
    public function categories() { return $this->belongsToMany(Category::class, 'category_listing'); }
    public function isLikedBy($user)
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
