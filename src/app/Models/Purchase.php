<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'user_id',
        'listing_id',
        'payment',
        'shipping_post_code',
        'shipping_address',
        'shipping_building_name',
    ];

    // 支払方法（payment）定数
    public const PAYMENT_CONVENIENCE = 0;
    public const PAYMENT_CREDIT = 1;

    // 支払方法ラベル
    public const PAYMENT_LABELS = [
        self::PAYMENT_CONVENIENCE => 'コンビニ払い',
        self::PAYMENT_CREDIT => 'カード払い',
    ];

    // 支払方法ラベル取得用
    public function getPaymentLabelAttribute()
    {
        return self::PAYMENT_LABELS[$this->payment];
    }

    // Relations
    public function user() { return $this->belongsTo(User::class); }
    public function listing() { return $this->belongsTo(Listing::class); }
}
